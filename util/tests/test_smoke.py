"""
Smoke test for the data update scripts.

Runs the same main.py commands as the nightly data update (.github/workflows/update_data.yaml) against a fake
TBA + supabase backend, so dependency updates can be checked in CI without credentials or network access.
"""
import datetime
import json
import os
import subprocess
import sys
from pathlib import Path

import pytest

from fake_backend import FakeBackend

UTIL_DIR = Path(__file__).resolve().parent.parent

# The scripts load data relative to today's date, so build the fixture data around the current year.
YEAR = datetime.date.today().year
PREV_YEAR = YEAR - 1

# Output that indicates a script caught an error and kept going, rather than crashing.
FAILURE_MARKERS = ("Traceback", "FAIL", "Failed", "Fake backend error")


def tba_event(key, event_type, event_type_string, name, start_date, end_date, week):
    return {
        "key": key,
        "event_type": event_type,
        "event_type_string": event_type_string,
        "name": name,
        "start_date": start_date,
        "end_date": end_date,
        "year": int(key[:4]),
        "week": week,
    }


def tba_award(event_key, award_type, name, team_numbers):
    return {
        "event_key": event_key,
        "award_type": award_type,
        "name": name,
        "recipient_list": [{"team_key": f"frc{n}", "awardee": None} for n in team_numbers],
    }


def build_tba_data():
    regional = tba_event(f"{YEAR}casj", 0, "Regional", "Silicon Valley Regional", f"{YEAR}-03-05", f"{YEAR}-03-08", 0)
    einstein = tba_event(f"{YEAR}cmptx", 4, "Championship Finals", "Einstein Field", f"{YEAR}-04-16", f"{YEAR}-04-19", None)
    # Offseason events don't award blue banners, so their awards should be ignored.
    offseason = tba_event(f"{YEAR}cc", 99, "Offseason", "Chezy Champs", f"{YEAR}-09-26", f"{YEAR}-09-28", None)
    events = [regional, einstein, offseason]

    teams = [
        {"team_number": 254, "nickname": "The Cheesy Poofs", "rookie_year": 1999, "country": "USA", "state_prov": "California"},
        {"team_number": 1678, "nickname": "Citrus Circuits", "rookie_year": 2005, "country": "USA", "state_prov": "California"},
        {"team_number": 9999, "nickname": "Smoke Test Rookies", "rookie_year": YEAR, "country": "USA", "state_prov": "Texas"},
    ]

    data = {
        f"/events/{YEAR}": events,
        f"/events/{YEAR}/keys": [event["key"] for event in events],
        f"/event/{regional['key']}/awards": [
            tba_award(regional["key"], 1, "Regional Winners", [254, 1678]),
            tba_award(regional["key"], 0, "Regional FIRST Impact Award", [1678]),
            # Not a blue banner award.
            tba_award(regional["key"], 5, "Regional Volunteer of the Year", [9999]),
        ],
        f"/event/{einstein['key']}/awards": [
            tba_award(einstein["key"], 1, "Championship Winners", [254]),
        ],
        f"/event/{offseason['key']}/awards": [
            tba_award(offseason["key"], 1, "Winners", [9999]),
        ],
        f"/event/{regional['key']}/teams": [{"team_number": 254}, {"team_number": 1678}, {"team_number": 9999}],
        f"/event/{einstein['key']}/teams": [{"team_number": 254}, {"team_number": 1678}],
        "/teams/0": teams,
    }
    for event in events:
        data[f"/event/{event['key']}"] = event
    for team in teams:
        data[f"/team/frc{team['team_number']}"] = team
    return data


def build_existing_tables():
    """Data from previous nightly runs, which should be included in the stats."""
    return {
        "Event": [{"event_id": f"{PREV_YEAR}casj", "type": 0, "type_string": "Regional", "name": "Silicon Valley Regional",
                   "start_date": f"{PREV_YEAR}-03-06", "year": PREV_YEAR, "week": 1}],
        "BlueBanner": [{"id": 1, "id_string": f"Regional Winners (1, Robot) @ {PREV_YEAR}casj {PREV_YEAR}-03-09: 254",
                        "name": "Regional Winners", "type": "Robot", "event_id": f"{PREV_YEAR}casj",
                        "team_number": 254, "season": PREV_YEAR, "date": f"{PREV_YEAR}-03-09"}],
    }


def run_main(backend: FakeBackend, *args) -> str:
    env = dict(os.environ)
    # Make sure requests to the fake backend never go through a proxy.
    env["NO_PROXY"] = env["no_proxy"] = "127.0.0.1,localhost"
    result = subprocess.run(
        [sys.executable, "main.py", *args, f"--credentials={json.dumps(backend.credentials())}"],
        cwd=UTIL_DIR, env=env, capture_output=True, text=True, timeout=120)

    output = result.stdout + result.stderr
    print(output)
    assert result.returncode == 0, f"main.py {' '.join(args)} exited with {result.returncode}"
    for marker in FAILURE_MARKERS:
        assert marker not in output, f"main.py {' '.join(args)} reported a failure ({marker!r})"
    return output


def rows_by(backend: FakeBackend, table: str, key: str) -> dict:
    return {row[key]: row for row in backend.tables[table]}


@pytest.fixture(scope="module")
def backend():
    """Run the nightly data update commands, in order, against a single fake backend."""
    with FakeBackend(build_tba_data(), build_existing_tables()) as backend:
        backend.outputs = {
            "banner": run_main(backend, "banner", "--banner_mode=since_current_year"),
            "event": run_main(backend, "event", "--event_mode=since_current_year"),
            "team": run_main(backend, "team", "--team_mode=full"),
        }
        yield backend


def test_banners_loaded(backend):
    banners = [(b["event_id"], b["type"], b["team_number"]) for b in backend.tables["BlueBanner"]]
    assert sorted(banners) == sorted([
        (f"{PREV_YEAR}casj", "Robot", 254),
        (f"{YEAR}casj", "Robot", 254),
        (f"{YEAR}casj", "Robot", 1678),
        (f"{YEAR}casj", "Team", 1678),
        (f"{YEAR}cmptx", "Robot", 254),
    ])

    # Banners are inserted in one request per year, skipping banners that already exist.
    banner_posts = [query for method, path, query in backend.requests
                    if method == "POST" and path.endswith("/BlueBanner")]
    assert len(banner_posts) == 1
    assert ("on_conflict", "id_string") in banner_posts[0]


def test_banner_reload_skips_duplicates(backend):
    n_banners = len(backend.tables["BlueBanner"])
    output = run_main(backend, "banner", "--banner_mode=since_current_year")
    assert len(backend.tables["BlueBanner"]) == n_banners
    assert f"{YEAR} - # New: 0, # Fail: 0" in output


def test_events_loaded(backend):
    events = rows_by(backend, "Event", "event_id")
    assert {f"{YEAR}casj", f"{YEAR}cmptx", f"{YEAR}cc"} <= events.keys()
    assert events[f"{YEAR}casj"]["week"] == 1
    assert events[f"{YEAR}cmptx"]["type"] == 4


def test_event_data_computed(backend):
    event_data = rows_by(backend, "EventData", "event_id")
    assert event_data.keys() == {f"{YEAR}casj", f"{YEAR}cmptx", f"{YEAR}cc"}

    # Only banners won before each event count: 254 won the previous year's regional.
    assert event_data[f"{YEAR}casj"]["robot_bbq"] == pytest.approx(1 / 3)
    assert event_data[f"{YEAR}casj"]["team_bbq"] == 0

    # 254 has 2 prior robot banners and 1678 has 1, plus 1678's team banner.
    assert event_data[f"{YEAR}cmptx"]["robot_bbq"] == pytest.approx(1.5)
    assert event_data[f"{YEAR}cmptx"]["team_bbq"] == pytest.approx(0.5)

    appearances = {(a["team_number"], a["event_id"]) for a in backend.tables["Appearance"]}
    assert appearances == {(254, f"{YEAR}casj"), (1678, f"{YEAR}casj"), (9999, f"{YEAR}casj"),
                           (254, f"{YEAR}cmptx"), (1678, f"{YEAR}cmptx")}


def test_teams_loaded(backend):
    teams = rows_by(backend, "Team", "team_number")
    assert teams.keys() == {254, 1678, 9999}
    assert teams[254]["province"] == "California"


def test_team_data_computed(backend):
    team_data = rows_by(backend, "TeamData", "team_number")
    assert team_data.keys() == {254, 1678, 9999}

    assert team_data[254]["robot_banners"] == 3
    assert team_data[254]["robot_bbq"] == pytest.approx(3 / (YEAR - 1999 + 1))
    assert team_data[1678]["robot_banners"] == 1
    assert team_data[1678]["team_banners"] == 1
    assert team_data[9999]["robot_banners"] == 0
    assert team_data[9999]["team_banners"] == 0
