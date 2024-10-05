
import datetime
from joblib import Parallel, delayed

from bbq_stats import compute_bbq_contribution, compute_sauce_contribution, compute_rolling_contribution
from tba_api import TheBlueAllianceAPI
from supabase_api import SupabaseAPI


class TeamProcessor:

    def __init__(self, tba_api: TheBlueAllianceAPI, supabase_api: SupabaseAPI, n_jobs=8):
        self.tba_api = tba_api
        self.supabase_api = supabase_api
        self.n_jobs = n_jobs

        self.team_queue = []

    def load_all_team_info(self, verbose=True) -> dict:
        """
        Function to load all the team information. The reason we typically load all the teams is 
        because veteran-rookie teams can sometimes claim numbers that are earlier than 
        the most recent page pulled from TBA. 
        """
        # Keep loading teams until the pages stop existing
        page_exists = True
        page_idx = 0
        most_recent_team = 0

        report = {}
        while page_exists:
            # Pull a batch of ~500 teams. If there are no teams in the batch,
            # then the page is assumed to not exist and all teams must be loaded.
            team_batch = self.load_teams_from_page(page_idx)
            if len(team_batch) == 0:
                page_exists = False
                break

            # Push the batch to supabase.
            res = self.supabase_api.upsert_batch(team_batch, "Team")

            # Track information for the report.
            report[str(page_idx)] = res
            most_recent_team = team_batch[-1]['team_number']

            # Clear the team queue for the next run so we aren't submitting stale data.
            self.clear_team_queue()
            page_idx += 1

        if verbose:
            print(f"Last team: {most_recent_team}")

        return report

    def load_teams_from_page(self, page):
        team_page_data = self.tba_api.get_data(f"/teams/{page}")

        for team in team_page_data.json():
            team_info = {
                "nickname": team['nickname'],
                "team_number": team['team_number'],
                "rookie_year": team['rookie_year'],
                "country": team['country'],
                "province": team['state_prov']
            }
            self.team_queue.append(team_info)

        return self.team_queue

    def _get_banners(self, team_number, banner_type):
        bb_filter = [
            {
                "column": "team_number",
                "value": team_number,
                "operation": "eq"
            },
            {
                "column": "type",
                "value": banner_type,
                "operation": "eq"
            }
        ]

        banners = self.supabase_api.get_data('BlueBanner',
                                             'event_id, type, team_number, id_string, season, Event!inner(event_id, year)',
                                             bb_filter)
        banners_dict = banners.model_dump()
        blue_banners = []
        if "data" in banners_dict:
            blue_banners = banners_dict['data']

        return blue_banners

    def load_team_data(self, current_year: float, cur_week: float, max_official_week: int) -> dict:
        report = []

        # Load all the team information available.
        team_data = self.supabase_api.get_paged_data("Team", "team_number, rookie_year", order_info={
                                                     'column': 'team_number', 'desc': False})

        for page in team_data:
            team_data_queue = []

            # Compute team statistics in parallel, since this is a time-costly operation to do sequentially.
            # Use the threading backend to ensure self.event_queue is actually updated.
            page_data = page.model_dump()['data']
            Parallel(n_jobs=self.n_jobs, backend="threading")(delayed(self.compute_single_team_data)(team, current_year, team_data_queue, cur_week, max_official_week)
                                                              for team in page_data)

            # Upsert the data.
            res_info = self.supabase_api.upsert_batch(team_data_queue, "TeamData")
            report.append(res_info)

        return report

    def compute_single_team_data(self, team: dict, current_year: int, team_data_queue: list, cur_week: float, max_official_week: int):
        robot_bbq = 0
        team_bbq = 0
        robot_sauce = 0
        team_sauce = 0
        robot_briquette = 0
        team_briquette = 0
        robot_ribs = 0
        team_ribs = 0

        # Extract the team information if it exists. Some team information is very null because the team
        # folded quickly or didn't participate in any events.
        if 'team_number' in team and 'rookie_year' in team and team['team_number'] is not None and team['rookie_year'] is not None:
            team_number = team['team_number']
            rookie_year = team['rookie_year']

            team_duration = (current_year - rookie_year) + 1
            sauce_duration = (current_year - 2005) + 1

            # If a team is a rookie for an upcoming season, set the team duration to 1 year.
            if team_duration <= 0:
                team_duration = 1

            # Teams founded after 2005 have BBQ = SAUCE.
            if team_duration < sauce_duration:
                sauce_duration = team_duration

            # Get all robot and team awards separately
            robot_banners = self._get_banners(team_number, "Robot")
            team_banners = self._get_banners(team_number, "Team")

            # TODO: Team BBQ and SAUCE are banners per season metrics, and really should be computed
            # based on active seasons only. However, that has been skipped for simplicity in the current
            # version. Eventually team appearances should be used to determine these stats more precisely
            # for inactive teams or teams with gaps in their history due to a hiatus.
            
            # BBQ
            robot_banner_count = compute_bbq_contribution(robot_banners)
            robot_bbq = robot_banner_count / team_duration
            team_banner_count = compute_bbq_contribution(team_banners)
            team_bbq = team_banner_count / team_duration

            # SAUCE
            robot_sauce = compute_sauce_contribution(
                robot_banners, current_year) / sauce_duration
            team_sauce = compute_sauce_contribution(
                team_banners, current_year) / sauce_duration

            # BRIQUETTE
            robot_briquette = compute_rolling_contribution(
                robot_banners, current_year, 4, cur_week, max_official_week) / 4
            team_briquette = compute_rolling_contribution(
                team_banners, current_year, 4, cur_week, max_official_week) / 4

            # RIBS
            robot_ribs = compute_rolling_contribution(
                robot_banners, current_year, 1, cur_week, max_official_week)
            team_ribs = compute_rolling_contribution(
                team_banners, current_year, 1, cur_week, max_official_week)

            # Add to the team data update packet.
            team_data = {
                "team_number": team_number,
                "robot_banners": robot_banner_count,
                "team_banners": team_banner_count,
                "robot_bbq": robot_bbq,
                "team_bbq": team_bbq,
                "robot_sauce": robot_sauce,
                "team_sauce": team_sauce,
                "robot_briquette": robot_briquette,
                "team_briquette": team_briquette,
                "robot_ribs": robot_ribs,
                "team_ribs": team_ribs
            }
            team_data_queue.append(team_data)
        else:
            print("Invalid team info detected")
            print(team)

    def clear_team_queue(self):
        self.team_queue.clear()
