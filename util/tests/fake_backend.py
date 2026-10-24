"""
An in-memory stand-in for The Blue Alliance API and supabase (auth + PostgREST), used by the smoke tests.

It only implements the parts of each API that the data update scripts use, which is enough to run main.py
end-to-end through the real supabase/postgrest/httpx/requests stack without network access or credentials.
"""
import base64
import json
import re
import threading
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer
from urllib.parse import parse_qsl, urlsplit

TBA_PREFIX = "/api/v3"
AUTH_PREFIX = "/auth/v1"
REST_PREFIX = "/rest/v1"

# Primary key column(s) for each table, used to resolve upsert conflicts when no on_conflict is given.
PRIMARY_KEYS = {
    "BlueBanner": "id",
    "Event": "event_id",
    "EventData": "event_id",
    "Team": "team_number",
    "TeamData": "team_number",
    "Appearance": "id_str",
}

# Columns that the database generates when a row is inserted.
AUTO_INCREMENT_COLUMNS = {
    "BlueBanner": "id",
}


def fake_jwt(payload: dict) -> str:
    """Build an (unsigned) JWT, since the supabase client expects its keys and tokens to look like one."""
    def encode(part: dict) -> str:
        return base64.urlsafe_b64encode(json.dumps(part).encode()).decode().rstrip("=")

    return f"{encode({'alg': 'HS256', 'typ': 'JWT'})}.{encode(payload)}.signature"


ANON_KEY = fake_jwt({"role": "anon", "iss": "supabase", "exp": 4102444800})
USER_ID = "00000000-0000-0000-0000-000000000001"


def _split_top_level(text: str) -> list:
    """Split a select string on commas that aren't inside an embedded resource's parentheses."""
    parts, depth, current = [], 0, ""
    for char in text:
        if char == "," and depth == 0:
            parts.append(current.strip())
            current = ""
            continue
        depth += char == "("
        depth -= char == ")"
        current += char
    if current.strip():
        parts.append(current.strip())
    return parts


def _parse_value(value: str):
    if value == "null":
        return None
    if re.fullmatch(r"-?\d+", value):
        return int(value)
    return value


def _compare(row_value, operation: str, value) -> bool:
    if operation == "is":
        return row_value is None if value is None else row_value == value
    if row_value is None:
        return False
    # PostgREST compares numbers numerically, but query values arrive as strings.
    if isinstance(row_value, (int, float)) and isinstance(value, str):
        value = float(value)
    elif isinstance(row_value, str):
        value = str(value)
    return {
        "eq": row_value == value,
        "neq": row_value != value,
        "lt": row_value < value,
        "lte": row_value <= value,
        "gt": row_value > value,
        "gte": row_value >= value,
    }[operation]


class FakeBackend:
    """Serves the fake APIs on localhost from a background thread. Use as a context manager."""

    def __init__(self, tba_data: dict, tables: dict = None):
        # TBA responses keyed by the path after /api/v3 (e.g. "/events/2026/keys").
        self.tba_data = tba_data
        # Database rows keyed by table name.
        self.tables = {name: [] for name in PRIMARY_KEYS}
        for name, rows in (tables or {}).items():
            self.tables[name] = [dict(row) for row in rows]
        self.next_ids = {table: max((row[column] for row in self.tables[table]), default=0) + 1
                         for table, column in AUTO_INCREMENT_COLUMNS.items()}
        # Every request received, as (method, path, query) tuples, so tests can check how the APIs were used.
        self.requests = []
        self.lock = threading.Lock()
        self.server = None

    @property
    def base_url(self) -> str:
        host, port = self.server.server_address[:2]
        return f"http://{host}:{port}"

    def credentials(self) -> dict:
        """Credentials in the format main.py expects, pointing at this fake backend."""
        return {
            "tba": {"base_url": f"{self.base_url}{TBA_PREFIX}", "api_key": "fake-tba-key"},
            "supabase": {
                "base_url": self.base_url,
                "api_key": ANON_KEY,
                "email": "smoke-test@example.com",
                "pass": "fake-password",
            },
        }

    def __enter__(self):
        backend = self

        class Handler(BaseHTTPRequestHandler):
            def log_message(self, format, *args):
                pass

            def do_GET(self):
                backend._handle(self, "GET")

            def do_HEAD(self):
                backend._handle(self, "HEAD")

            def do_POST(self):
                backend._handle(self, "POST")

            def do_DELETE(self):
                backend._handle(self, "DELETE")

        self.server = ThreadingHTTPServer(("127.0.0.1", 0), Handler)
        threading.Thread(target=self.server.serve_forever, daemon=True).start()
        return self

    def __exit__(self, *exc):
        self.server.shutdown()
        self.server.server_close()

    # --- Request routing ---

    def _handle(self, handler: BaseHTTPRequestHandler, method: str):
        url = urlsplit(handler.path)
        query = parse_qsl(url.query, keep_blank_values=True)
        length = int(handler.headers.get("Content-Length") or 0)
        body = json.loads(handler.rfile.read(length)) if length else None

        with self.lock:
            self.requests.append((method, url.path, query))
            try:
                if url.path.startswith(TBA_PREFIX):
                    status, payload, headers = self._tba(handler, url.path[len(TBA_PREFIX):])
                elif url.path.startswith(AUTH_PREFIX):
                    status, payload, headers = self._auth(url.path[len(AUTH_PREFIX):])
                elif url.path.startswith(REST_PREFIX):
                    table = url.path[len(REST_PREFIX):].strip("/")
                    status, payload, headers = self._rest(handler, method, table, query, body)
                else:
                    status, payload, headers = 404, {"message": "Not found"}, {}
            except Exception as e:
                status, payload, headers = 500, {"message": f"Fake backend error: {e!r}"}, {}

        data = b"" if payload is None else json.dumps(payload).encode()
        handler.send_response(status)
        handler.send_header("Content-Type", "application/json")
        handler.send_header("Content-Length", str(len(data)))
        for key, value in headers.items():
            handler.send_header(key, value)
        handler.end_headers()
        if method != "HEAD":
            handler.wfile.write(data)

    def _tba(self, handler, path: str):
        if handler.headers.get("X-TBA-Auth-Key") is None:
            return 401, {"Error": "X-TBA-Auth-Key is a required header"}, {}
        if path in self.tba_data:
            return 200, self.tba_data[path], {}
        # TBA returns empty lists for list endpoints with no data (e.g. a future year's events).
        if re.fullmatch(r"/(events/\d+(/keys)?|teams/\d+|event/\w+/(awards|teams))", path):
            return 200, [], {}
        return 404, {"Error": f"{path} not found"}, {}

    def _auth(self, path: str):
        if path == "/token":
            user = {
                "id": USER_ID,
                "aud": "authenticated",
                "role": "authenticated",
                "email": "smoke-test@example.com",
                "app_metadata": {"provider": "email"},
                "user_metadata": {},
                "created_at": "2026-01-01T00:00:00Z",
            }
            session = {
                "access_token": fake_jwt({"sub": USER_ID, "role": "authenticated", "exp": 4102444800}),
                "refresh_token": "fake-refresh-token",
                "token_type": "bearer",
                "expires_in": 3600,
                "expires_at": 4102444800,
                "user": user,
            }
            return 200, session, {}
        if path == "/logout":
            return 204, None, {}
        return 404, {"message": f"{path} not found"}, {}

    # --- PostgREST ---

    def _rest(self, handler, method: str, table: str, query: list, body):
        if table not in self.tables:
            return 404, {"code": "42P01", "message": f'relation "public.{table}" does not exist'}, {}

        prefer = handler.headers.get("Prefer", "")
        params = dict(query)
        filters = [(key, value) for key, value in query
                   if key not in ("select", "order", "offset", "limit", "on_conflict", "columns")]

        if method in ("GET", "HEAD"):
            order = params["order"].partition(".") if "order" in params else None
            rows = self._select(table, params.get("select", "*"), filters, order)
            total = len(rows)
            offset = int(params.get("offset", 0))
            limit = int(params["limit"]) if "limit" in params else None
            rows = rows[offset:offset + limit if limit is not None else None]

            headers = {}
            if "count=" in prefer:
                end = f"{offset}-{offset + len(rows) - 1}" if rows else "*"
                headers["Content-Range"] = f"{end}/{total}"
            return 200, rows, headers

        if method == "POST":
            rows = body if isinstance(body, list) else [body]
            on_conflict = params.get("on_conflict", PRIMARY_KEYS[table])
            ignore = "resolution=ignore-duplicates" in prefer
            changed = self._upsert(table, rows, on_conflict, ignore)
            return 201, changed if "return=representation" in prefer else None, {}

        if method == "DELETE":
            to_delete = self._select(table, "*", filters)
            self.tables[table] = [row for row in self.tables[table] if row not in to_delete]
            return 200, to_delete if "return=representation" in prefer else None, {}

        return 405, {"message": f"{method} not supported"}, {}

    def _select(self, table: str, select: str, filters: list, order=None) -> list:
        rows = [row for row in self.tables[table] if self._matches(row, filters)]
        # Sort before selecting columns, since the order column doesn't have to be selected.
        if order is not None:
            column, _, direction = order
            rows = sorted(rows, key=lambda row: row[column], reverse=direction.startswith("desc"))

        columns = _split_top_level(select)
        result = []
        for row in rows:
            out = {}
            keep = True
            for column in columns:
                embed = re.fullmatch(r"(\w+)(!inner)?\((.*)\)", column)
                if embed:
                    # Embed the related row, joined on the embedded table's primary key.
                    other, inner, other_columns = embed.groups()
                    key = PRIMARY_KEYS[other]
                    match = next((r for r in self.tables[other] if r[key] == row.get(key)), None)
                    if match is None and inner:
                        keep = False
                        break
                    out[other] = None if match is None else self._project(match, _split_top_level(other_columns))
                else:
                    out.update(self._project(row, [column]))
            if keep:
                result.append(out)
        return result

    @staticmethod
    def _project(row: dict, columns: list) -> dict:
        if columns == ["*"]:
            return dict(row)
        return {column: row.get(column) for column in columns}

    @staticmethod
    def _matches(row: dict, filters: list) -> bool:
        for column, expression in filters:
            operation, _, value = expression.partition(".")
            if not _compare(row.get(column), operation, _parse_value(value)):
                return False
        return True

    def _upsert(self, table: str, rows: list, on_conflict: str, ignore_duplicates: bool) -> list:
        keys = [key.strip() for key in on_conflict.split(",")]
        changed = []
        for row in rows:
            existing = next((r for r in self.tables[table]
                             if all(key in row and r.get(key) == row[key] for key in keys)), None)
            if existing is not None:
                if not ignore_duplicates:
                    existing.update(row)
                    changed.append(dict(existing))
                continue

            new_row = dict(row)
            auto_column = AUTO_INCREMENT_COLUMNS.get(table)
            if auto_column and auto_column not in new_row:
                new_row[auto_column] = self.next_ids[table]
                self.next_ids[table] += 1
            self.tables[table].append(new_row)
            changed.append(dict(new_row))
        return changed
