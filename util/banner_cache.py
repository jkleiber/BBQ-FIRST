from supabase_api import SupabaseAPI

# Superset of the columns needed by the event and team processors.
# The "!inner" hint drops banners that are not associated with an event.
BANNER_COLUMNS = 'event_id, type, team_number, id_string, season, Event!inner(event_id, start_date, year)'


async def load_banners_by_team(supabase_api: SupabaseAPI) -> dict:
    """
    Load every blue banner in a handful of paged requests and group them by (team_number, type).
    This is much cheaper than querying supabase for each team individually, which generated tens of
    thousands of requests (and their associated egress and logs) per data update.
    """
    paged_data = await supabase_api.get_paged_data('BlueBanner', BANNER_COLUMNS, order_info={
        'column': 'id', 'desc': False})

    banners_by_team = {}
    for page in paged_data:
        for banner in page.model_dump()['data']:
            key = (banner['team_number'], banner['type'])
            banners_by_team.setdefault(key, []).append(banner)

    return banners_by_team
