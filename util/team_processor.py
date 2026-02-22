
import asyncio

from bbq_stats import compute_bbq_contribution, compute_sauce_contribution, compute_rolling_contribution
from tba_api import TheBlueAllianceAPI
from supabase_api import SupabaseAPI, MAX_ROWS_PER_REQUEST


class TeamProcessor:

    def __init__(self, tba_api: TheBlueAllianceAPI, supabase_api: SupabaseAPI, n_jobs=8, max_retries=2):
        self.tba_api = tba_api
        self.supabase_api = supabase_api
        self.n_jobs = n_jobs

        self.team_queue = []

        # Handle retries up to a maximum amount.
        self.team_attempt_queue = []
        self.team_retry_status = {}
        self.max_retries = max_retries

        # Limit the number of jobs that can run at once.
        self.sem = asyncio.Semaphore(n_jobs)

    async def load_all_team_info(self, verbose=True) -> dict:
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
            res = await self.supabase_api.upsert_batch(team_batch, "Team")

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

    async def _get_banners(self, team_number, banner_type):
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

        banners = await self.supabase_api.get_data('BlueBanner',
                                             'event_id, type, team_number, id_string, season, Event!inner(event_id, year)',
                                             bb_filter)
        banners_dict = banners.model_dump()
        blue_banners = []
        if "data" in banners_dict:
            blue_banners = banners_dict['data']

        return blue_banners
    
    async def throttled_compute(self, team, current_year, team_data_queue, cur_week, max_official_week):
        async with self.sem:
            await self.compute_single_team_data(team, current_year, team_data_queue, cur_week, max_official_week)

    async def load_team_data(self, current_year: float, cur_week: float, max_official_week: int, verbose=False) -> dict:
        report = []

        self.team_attempt_queue = []

        # Load all the team information available.
        team_data = await self.supabase_api.get_paged_data("Team", "team_number, rookie_year", order_info={
                                                     'column': 'team_number', 'desc': False})

        # Load up the team attempt queue.
        for page in team_data:
            page_data = page.model_dump()['data']
            self.team_attempt_queue.extend(page_data)
            
            print(f"# Teams remaining: {len(self.team_attempt_queue)}")

        print("------ Starting team data processing ------")

        # Run team statistics until the queue is empty (via successful runs or exceeding the maximum number of retries).
        while len(self.team_attempt_queue) > 0:
            team_data_queue = []

            # Only do at most the maximum supabase pages
            slice_max_index = len(self.team_attempt_queue)
            if MAX_ROWS_PER_REQUEST < slice_max_index:
                slice_max_index = MAX_ROWS_PER_REQUEST

            # Compute team statistics in parallel, since this is a time-costly operation to do sequentially.
            # Use the threading backend to ensure class member variables are actually updated.
            team_attempt_slice = self.team_attempt_queue[0:slice_max_index]
            tasks = [
                self.throttled_compute(team, current_year, team_data_queue, cur_week, max_official_week)
                for team in team_attempt_slice
            ]
            await asyncio.gather(*tasks)

            # Pop all the successfully processed teams from the team attempt queue.
            i = 0
            iterations = 0
            while iterations < slice_max_index:
                team_number = self.team_attempt_queue[i]['team_number']
                if self.should_retry_team(team_number) is False:
                    del self.team_attempt_queue[i]
                    # Don't increment i here because a new element should have moved into the spot.
                else:
                    i += 1

                # Always increment the number of iterations to prevent array out of bounds, or clearing the entire queue.
                iterations += 1

            # # Upsert the data.
            res_info = await self.supabase_api.upsert_batch(team_data_queue, "TeamData")
            report.append(res_info)

            print(f"# Teams remaining: {len(self.team_attempt_queue)}")

        return report

    async def compute_single_team_data(self, team: dict, current_year: int, team_data_queue: list, cur_week: float, max_official_week: int):
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
            try:
                robot_banners = await self._get_banners(team_number, "Robot")
            except Exception as e:
                print(f"{type(e).__name__} occurred for team {team}")
                self.request_retry_team(team)
                return
            
            try:
                team_banners = await self._get_banners(team_number, "Team")
            except Exception as e:
                print(f"{type(e).__name__} occurred for team {team}")
                self.request_retry_team(team)
                return

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

            self.report_team_successfully_processed(team)
        else:
            print(f"Invalid team info detected for {team}")

    def request_retry_team(self, team):
        team_number = team["team_number"]
        if team_number not in self.team_retry_status.keys():
            self.team_retry_status[team_number] = {
                "num_retries": 0
            }
        else:
            self.team_retry_status[team_number]['num_retries'] += 1

        if self.team_retry_status[team_number]['num_retries'] < self.max_retries:
            print(f"Retry #{self.team_retry_status[team_number]['num_retries'] + 1} for {team_number} queued")

    def report_team_successfully_processed(self, team):
        team_number = team["team_number"]
        if team_number not in self.team_retry_status.keys():
            # No reporting is required for teams that have processing succeed on the first try.
            return
        
        self.team_retry_status[team_number]['is_success'] = True

    def should_retry_team(self, team_number) -> bool:
        if team_number not in self.team_retry_status.keys():
            return False 
        
        if self.team_retry_status[team_number]['num_retries'] > self.max_retries:
            return False
        
        if 'is_success' in self.team_retry_status[team_number] and self.team_retry_status[team_number]['is_success'] is True:
            return False
        
        return True

    def clear_team_queue(self):
        self.team_queue.clear()
