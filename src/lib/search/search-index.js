// Columns needed to build the search autocomplete labels.
export const TEAM_INDEX_COLUMNS = "team_number, nickname";
export const EVENT_INDEX_COLUMNS = "event_id, year, name";

// Supabase paginates requests at 1000 rows.
const ROWS_PER_REQUEST = 1000;

// Load every row of a table, ordered by orderColumn so the pages don't overlap or skip rows.
export async function loadAllRows(supabase, table, columns, orderColumn) {
    const { count, error: countError } = await supabase.from(table).select(columns, { count: 'exact', head: true });
    if (countError) {
        throw countError;
    }

    let rows = [];
    for (let startIndex = 0; startIndex < count; startIndex += ROWS_PER_REQUEST) {
        const { data, error } = await supabase.from(table)
            .select(columns)
            .order(orderColumn, { ascending: true })
            .range(startIndex, startIndex + ROWS_PER_REQUEST - 1);

        if (error) {
            throw error;
        }

        rows = rows.concat(data);
    }

    return rows;
}

// Build the search index ({ teams, events }) from the database.
export async function buildSearchIndex(supabase) {
    const teams = await loadAllRows(supabase, "Team", TEAM_INDEX_COLUMNS, "team_number");
    const events = await loadAllRows(supabase, "Event", EVENT_INDEX_COLUMNS, "event_id");

    return { teams, events };
}
