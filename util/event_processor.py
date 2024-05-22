

from joblib import Parallel, delayed
from supabase_api import SupabaseAPI
from tba_api import TheBlueAllianceAPI


class EventProcessor:

    def __init__(self, tba_api: TheBlueAllianceAPI, supabase_api: SupabaseAPI, n_jobs=8):
        self.tba_api = tba_api
        self.supabase_api = supabase_api
        self.n_jobs = n_jobs

        self.event_queue = []
        self.event_data_queue = []

    def load_year_event_info(self, year: int):
        year_events_data = self.tba_api.get_data(f"/events/{year}")

        for event in year_events_data.json():
            week = 1
            if 'week' in event and event['week'] is not None:
                # Note: Event week is zero-indexed for some reason, so add 1.
                week = event['week'] + 1

            event_info = {
                "event_id": event['key'],
                "type": event['event_type'],
                "type_string": event['event_type_string'],
                "name": event['name'],
                "start_date": event['start_date'],
                "year": event['year'],
                "week": week
            }
            self.event_queue.append(event_info)

    def get_prior_banners(self, start_date, event_id: str, team_number: int, banner_type: str):
        # Only consider blue banners won by this team at earlier events to this one.
        # Some events feed into other events, and give awards on the same day (i.e. Championship divisions
        # award blue banners the same day that Einstein does). To handle these scenarios, we must check
        # that an event associated with a blue banner has an earlier start date than the current event.
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
            },
            {
                "column": "Event.start_date",
                "value": start_date,
                "operation": "lt"
            },
            {
                "column": "event_id",
                "value": event_id,
                "operation": "neq"
            }
        ]

        # In order to filter out the BlueBanner rows based on the foreign key constraints
        # (Event.start_date < event start date), the "!inner" hint is needed. Without this
        # hint, the query will return all BlueBanners won by a team with "Event: None"
        # rather than applying the correct filter.
        banners = self.supabase_api.get_data('BlueBanner',
                                             'event_id, type, team_number, id_string, Event!inner(event_id, start_date, year)',
                                             bb_filter)
        banners_dict = banners.model_dump()
        team_blue_banners = []
        if 'data' in banners_dict:
            team_blue_banners = banners_dict['data']

        return team_blue_banners

    def compute_bbq_contribution(self, blue_banners: dict):
        num_banners = len(blue_banners)
        return num_banners

    def compute_sauce_contribution(self, blue_banners: dict, event_year: int):
        # Note: Any event pre-2005 will have 0 sauce.
        if event_year < 2005:
            return 0

        sauce_banners = [banner for banner in blue_banners if banner['Event']['year'] >= 2005]
        num_banners = len(sauce_banners)

        return num_banners

    def compute_rolling_contribution(self, blue_banners: dict, event_year: int, n_seasons: int):
        # Only consider blue banners won by this team at earlier events to this one, and only including the
        # most recent N seasons (plus the season in progress). For example, a BRIQUETTE (N=4) from 2024 will include
        # 2024, 2023, 2022, 2021 and 2020 banners.
        # Some events feed into other events, and give awards on the same day (i.e. Championship divisions
        # award blue banners the same day that Einstein does). To handle these scenarios, we must check
        # that an event associated with a blue banner has an earlier start date than the current event.
        min_year = event_year - n_seasons
        rolling_banners = [banner for banner in blue_banners if banner['Event']['year'] >= min_year]
        num_banners = len(rolling_banners)

        return num_banners

    def compute_event_statistics(self, event_info: dict):
        event_id = event_info['event_id']
        event_teams_data = self.tba_api.get_data(f"/event/{event_id}/teams")

        if event_teams_data is None:
            return

        # Raw totals.
        n_teams = 0
        n_banners_robot_bbq = 0
        n_banners_team_bbq = 0
        n_banners_robot_sauce = 0
        n_banners_team_sauce = 0
        n_banners_robot_briquette = 0
        n_banners_team_briquette = 0
        n_banners_robot_ribs = 0
        n_banners_team_ribs = 0

        # Aggregated data.
        robot_bbq = 0
        team_bbq = 0
        robot_sauce = 0
        team_sauce = 0
        robot_briquette = 0
        team_briquette = 0
        robot_ribs = 0
        team_ribs = 0
        appearances = []
        try:
            for team in event_teams_data.json():
                n_teams += 1
                team_number = team['team_number']

                # Register that the team appeared at the event.
                appearance = {
                    'id_str': f"{team_number} @ {event_id}",
                    'team_number': team_number,
                    'event_id': event_id
                }
                appearances.append(appearance)

                # Get the blue banners won by this particular team prior to this event's start,
                # and use that to aggregate the various stats.
                robot_banners = self.get_prior_banners(
                    event_info['start_date'], event_id, team_number, "Robot")
                team_banners = self.get_prior_banners(
                    event_info['start_date'], event_id, team_number, "Team")

                # BBQ
                n_banners_robot_bbq += self.compute_bbq_contribution(robot_banners)
                n_banners_team_bbq += self.compute_bbq_contribution(team_banners)

                # SAUCE (BBQ since 2005)
                n_banners_robot_sauce += self.compute_sauce_contribution(
                    robot_banners, event_info['year'])
                n_banners_team_sauce += self.compute_sauce_contribution(
                    team_banners, event_info['year'])

                # BRIQUETTE (BBQ only using the most recent 4 years)
                n_banners_robot_briquette += self.compute_rolling_contribution(
                    robot_banners, event_info['year'], 4)
                n_banners_team_briquette += self.compute_rolling_contribution(
                    team_banners, event_info['year'], 4)

                # RIBS (BBQ only using the most recent year)
                n_banners_robot_ribs += self.compute_rolling_contribution(
                    robot_banners, event_info['year'], 1)
                n_banners_team_ribs += self.compute_rolling_contribution(
                    team_banners, event_info['year'], 1)

            # Compute stats (BBQ, SAUCE, BRIQUETTE, RIBS)
            if n_teams > 0:
                robot_bbq = n_banners_robot_bbq / n_teams
                team_bbq = n_banners_team_bbq / n_teams
                robot_sauce = n_banners_robot_sauce / n_teams
                team_sauce = n_banners_team_sauce / n_teams
                robot_briquette = n_banners_robot_briquette / n_teams
                team_briquette = n_banners_team_briquette / n_teams
                robot_ribs = n_banners_robot_ribs / n_teams
                team_ribs = n_banners_team_ribs / n_teams

                # Delete all teams from the attendance list. This allows event
                # appearances to be completely refreshed later when the appearance
                # queue is upserted.
                appearance_filter = [{
                    "column": "event_id",
                    "value": event_id,
                    "operation": "eq"
                }]
                self.supabase_api.delete_rows("Appearance", appearance_filter)

                # Update appearances for each event in order to avoid events being momentarily blank.
                _ = self.supabase_api.upsert_batch(appearances, "Appearance")
        except Exception as e:
            # If there is an error, just keep going.
            # We will set the event data to 0.
            pass

        # Create an EventData item.
        event_data = {
            "event_id": event_info['event_id'],
            "robot_bbq": robot_bbq,
            "team_bbq": team_bbq,
            "robot_sauce": robot_sauce,
            "team_sauce": team_sauce,
            "robot_briquette": robot_briquette,
            "team_briquette": team_briquette,
            "robot_ribs": robot_ribs,
            "team_ribs": team_ribs
        }
        self.event_data_queue.append(event_data)

    def compute_event_queue_statistics(self):
        # Compute event statistics in parallel, since this is a time-costly operation to do sequentially.
        # Use the threading backend to ensure self.event_queue is actually updated.
        Parallel(n_jobs=self.n_jobs, backend="threading")(delayed(self.compute_event_statistics)(event)
                                                          for event in self.event_queue)

    def load_year_events(self, year, update_data=True):
        # First load the event info.
        self.load_year_event_info(year)

        # Submit the results to supabase.
        res_info = self.supabase_api.upsert_batch(self.event_queue, "Event")

        # Compute event statistics for the items in the event queue.
        res_data = {}
        if update_data:
            self.compute_event_queue_statistics()
            res_data = self.supabase_api.upsert_batch(self.event_data_queue, "EventData")

        # Clear the event queue to avoid spamming the same events repeatedly.
        self.clear_queues()

        return {"year": year, "info": res_info, "data": res_data}

    def clear_queues(self):
        self.event_queue.clear()
        self.event_data_queue.clear()
