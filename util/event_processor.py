

from joblib import Parallel, delayed
from supabase_api import SupabaseAPI
from tba_api import TheBlueAllianceAPI
from bbq_stats import compute_bbq_contribution, compute_sauce_contribution, compute_rolling_contribution
from tba_banner_processor import VALID_EVENT_TYPES

# World championship event types.
# See more here: https://github.com/the-blue-alliance/the-blue-alliance/blob/master/consts/event_type.py#L2
WCMP_EVENT_TYPES = (3, 4, 6)

class EventProcessor:

    def __init__(self, tba_api: TheBlueAllianceAPI, supabase_api: SupabaseAPI, n_jobs=8):
        self.tba_api = tba_api
        self.supabase_api = supabase_api
        self.n_jobs = n_jobs

        self.event_queue = []
        self.event_data_queue = []

        # Timing information for all loaded years.
        self.max_week_of_year = {}
        self.max_date_of_year = {}
        self.min_date_of_year = {}

    def load_year_event_info(self, year: int):
        year_events_data = self.tba_api.get_data(f"/events/{year}")

        for event in year_events_data.json():
            week = 1
            if 'week' in event and event['week'] is not None:
                # Note: Event week is zero-indexed for some reason, so add 1.
                week = event['week'] + 1

            # Track the min/max dates and max week for a given year.
            # The assumption is that the championships are 1 week greater than the maximum week.
            if event['event_type'] in VALID_EVENT_TYPES:
                if year in self.max_week_of_year.keys() and self.max_week_of_year[year] < week:
                    self.max_week_of_year[year] = week
                elif year not in self.max_week_of_year.keys():
                    self.max_week_of_year[year] = week

                if year in self.max_date_of_year.keys() and self.max_date_of_year[year] < event['start_date'] and event['start_date'] is not None:
                    self.max_date_of_year[year] = event['start_date']
                elif year not in self.max_date_of_year.keys() and event['start_date'] is not None:
                    self.max_date_of_year[year] = event['start_date']

                if year in self.min_date_of_year.keys() and self.min_date_of_year[year] > event['start_date'] and event['start_date'] is not None:
                    self.min_date_of_year[year] = event['start_date']
                elif year not in self.min_date_of_year.keys() and event['start_date'] is not None:
                    self.min_date_of_year[year] = event['start_date']

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

    def get_season_timeline_info(self, year: int):
        start_date = None
        end_date = None
        end_week = 0

        if year in self.min_date_of_year.keys():
            start_date = self.min_date_of_year[year]
        if year in self.max_date_of_year.keys():
            end_date = self.max_date_of_year[year]
        if year in self.max_week_of_year.keys():
            end_week = self.max_week_of_year[year]

        return (start_date, end_date, end_week)

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

    def compute_event_statistics(self, event_info: dict):
        event_id = event_info['event_id']
        event_teams_data = self.tba_api.get_data(f"/event/{event_id}/teams")

        if event_teams_data is None:
            return
        

        # The championship is 1 week after the maximum week, because the championship 
        # week defaults to week 1 in TBA.
        champ_week = self.max_week_of_year[event_info['year']] + 1

        # Compute the actual event week.
        event_week = event_info['week']
        # If this is a championship (or a championship division / Festival of Champions), 
        # set the current week to the championship week. This will result in time-window 
        # based stats considering the current season at full strength, and the minimum 
        # season at 0 strength (i.e. the current season is fully faded in).
        if event_info['type'] in WCMP_EVENT_TYPES:
            event_week = self.max_week_of_year[event_info['year']] + 1
        elif event_info['type'] == 99:
            # If this is an offseason event, make the event week larger than the championship week.
            event_week = self.max_week_of_year[event_info['year']] + 2
        elif event_info['type'] == 100:
            # The event week is 0 if this is a preseason event.
            event_week = 0

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
                n_banners_robot_bbq += compute_bbq_contribution(robot_banners)
                n_banners_team_bbq += compute_bbq_contribution(team_banners)

                # SAUCE (BBQ since 2005)
                n_banners_robot_sauce += compute_sauce_contribution(
                    robot_banners, event_info['year'])
                n_banners_team_sauce += compute_sauce_contribution(
                    team_banners, event_info['year'])

                # BRIQUETTE (BBQ only using the most recent 4 years).
                n_banners_robot_briquette += compute_rolling_contribution(
                    robot_banners, event_info['year'], 4, event_week, champ_week)
                n_banners_team_briquette += compute_rolling_contribution(
                    team_banners, event_info['year'], 4, event_week, champ_week)

                # RIBS (BBQ only using the most recent year)
                n_banners_robot_ribs += compute_rolling_contribution(
                    robot_banners, event_info['year'], 1, event_week, champ_week)
                n_banners_team_ribs += compute_rolling_contribution(
                    team_banners, event_info['year'], 1, event_week, champ_week)

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
