

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
        self.appearance_queue = []

    def load_year_event_info(self, year: int):
        year_events_data = self.tba_api.get_data(f"/events/{year}")

        for event in year_events_data.json():
            week = 1
            if 'week' in event and event['week'] is not None:
                # Note: Event week is zero-indexed for some reason, so add 1.
                week = event['week'] + 1

            event_info = {
                "event_id": event['key'],
                "name": event['name'],
                "start_date": event['start_date'],
                "year": event['year'],
                "week": week
            }
            self.event_queue.append(event_info)

    def compute_bbq_contribution(self, start_date, team_number: int, banner_type: str):
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
                "column": "date",
                "value": start_date,
                "operation": "lt"
            }
        ]

        banners = self.supabase_api.get_data('BlueBanner', '*', bb_filter)
        banners_dict = banners.model_dump()
        num_banners = 0
        if 'data' in banners_dict:
            num_banners = len(banners_dict['data'])

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

        # Aggregated data.
        robot_bbq = 0
        team_bbq = 0
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
                self.appearance_queue.append(appearance)

                # Get the blue banners won by this particular team prior to this event's start date.
                n_banners_robot_bbq += self.compute_bbq_contribution(event_info['start_date'], team_number, "Robot")
                n_banners_team_bbq += self.compute_bbq_contribution(event_info['start_date'], team_number, "Team")

            # Compute BBQ (n_banners / n_teams)
            if n_teams > 0:
                robot_bbq = n_banners_robot_bbq / n_teams
                team_bbq = n_banners_team_bbq / n_teams
        except Exception as e:
            # If there is an error, just keep going.
            # We will set the event data to 0.
            pass
        
        # Create an EventData item.
        event_data = {
            "event_id": event_info['event_id'],
            "robot_bbq": robot_bbq,
            "team_bbq": team_bbq
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
        res_appearance = {}
        if update_data:
            self.compute_event_queue_statistics()
            res_data = self.supabase_api.upsert_batch(self.event_data_queue, "EventData")
            res_appearance = self.supabase_api.upsert_batch(self.appearance_queue, "Appearance")

        # Clear the event queue to avoid spamming the same events repeatedly.
        self.clear_queues()

        return {"year": year, "info": res_info, "data": res_data, "appear": res_appearance}

    def clear_queues(self):
        self.appearance_queue.clear()
        self.event_queue.clear()
        self.event_data_queue.clear()
