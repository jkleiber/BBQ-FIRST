import datetime
from joblib import Parallel, delayed

from tba_api import TheBlueAllianceAPI

# Events must be one of the following types to count for BBQ consideration.
# An event must be an official FRC event that awards a blue banner.
# See more here: https://github.com/the-blue-alliance/the-blue-alliance/blob/master/consts/event_type.py#L2
VALID_EVENT_TYPES = (0, 1, 2, 3, 4, 5, 6, 7)

# Only consider blue banner worthy awards. Split them up by robot and team awards.
# See more here: https://github.com/the-blue-alliance/the-blue-alliance/blob/master/consts/award_type.py#L6
ROBOT_BLUE_BANNER_AWARD_TYPES = (1, 74)
TEAM_BLUE_BANNER_AWARD_TYPES = (0, 3, 69, 80)


class TBABannerProcessor:

    def __init__(self, tba_api_info: dict, n_jobs=4, verbose=False):
        # Initialize the APIs that are used to process and update the awards.
        self.tba_api = TheBlueAllianceAPI(tba_api_info['base_url'], tba_api_info['api_key'])
        
        # Create a queue of banners to submit. This is done in order to allow parallel processing of all the events.
        # While the events are processed in parallel, joblib fails to pickle supabase calls. So the calls to the database are done in series.
        self.banner_queue = []

        # Define the maximum number of parallel jobs this can run when processing awards.
        self.n_jobs = n_jobs

        # Other flags.
        self.verbose = verbose

    def process_award_recipients(self, award_recipients: list, award_type: int, award_str: str, event_code: str, year: int):
        for recipient in award_recipients:
            team_key = recipient['team_key']

            # Don't allow team key to be None when querying TBA.
            if team_key is None:
                continue

            team_data = self.tba_api.get_data(f"/team/{team_key}")

            try:
                team_number = team_data.json()['team_number']
            except:
                # Debugging information.
                print(team_data)
                print(f"FAIL FAIL FAIL {team_key}")
                exit(-1)

            banner_type = ""
            if award_type in ROBOT_BLUE_BANNER_AWARD_TYPES:
                banner_type = "Robot"
            elif award_type in TEAM_BLUE_BANNER_AWARD_TYPES:
                banner_type = "Team"

            # Create an ID string based on the award attributes.
            # This string will be unique for a given team winning an award at an event.
            # This is used to ensure awards are not duplicated in the database.
            id_str = f"{award_str} ({award_type}, {banner_type}) @ {event_code}: {team_number}"

            # Create the award dictionary and add it to the queue of banners to send to the database later.
            # This is done because the supabase client cannot be pickled. Since it cannot be pickled, it 
            # cannot be a class member of AwardProcessor, as this would prevent joblib from multithreading.
            # This is due to joblib pickling AwardProcessor in any class function with the `self` keyword.
            banner_dict = {
                "id_string": id_str,
                "name": award_str,
                "type": banner_type,
                "event_id": event_code,
                "team_number": team_number,
                "season": year
            }
            self.banner_queue.append(banner_dict)

            # If selected, indicate what was queued.
            if self.verbose:
                print(id_str)

    def process_event(self, event_key: str, year: int):
        # Pull this event's data to determine if it is an official event.
        event_data = self.tba_api.get_data(f"/event/{event_key}")
        event_info = event_data.json()

        # If this is an official event, pull all the awards. We only consider the blue banner awards.
        if event_info['event_type'] in VALID_EVENT_TYPES:
            event_award_data = self.tba_api.get_data(f"/event/{event_key}/awards")

            for award_info in event_award_data.json():
                if award_info['award_type'] in TEAM_BLUE_BANNER_AWARD_TYPES or award_info['award_type'] in ROBOT_BLUE_BANNER_AWARD_TYPES:
                    self.process_award_recipients(
                        award_info['recipient_list'], award_info['award_type'], award_info['name'], award_info['event_key'], year)

    def pull_year_awards(self, year: int):
        # Request all event keys for the given year. This will be used to collect all relevant awards.
        event_keys_result = self.tba_api.get_data(f"/events/{year}/keys")

        # Process the events in parallel. The threading backend is used to ensure the banner_queue actually gets updated by the jobs. 
        # Using the default backend results in an empty list
        Parallel(n_jobs=self.n_jobs, backend="threading")(delayed(self.process_event)(key, year)
                                     for key in event_keys_result.json())
        
        return self.banner_queue

    def pull_all_awards(self):

        # First year of TBA data for event awards
        start_year = 1992

        # Get the current year
        today = datetime.date.today()
        cur_year = today.year

        # Load all awards for all time up to the current year.
        for year in range(start_year, cur_year+1):
            self.pull_year_awards(year)

        return self.banner_queue

    def clear_banner_queue(self):
        self.banner_queue.clear()
