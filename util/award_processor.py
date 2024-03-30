import datetime
from joblib import Parallel, delayed

from credentials import CredentialManager
from tba_api import TheBlueAllianceAPI

# Events must be one of the following types to count for BBQ consideration.
# An event must be an official FRC event that awards a blue banner.
# See more here: https://github.com/the-blue-alliance/the-blue-alliance/blob/master/consts/event_type.py#L2
VALID_EVENT_TYPES = (0, 1, 2, 3, 4, 5, 6, 7)

# Only consider blue banner worthy awards. Split them up by robot and team awards.
# See more here: https://github.com/the-blue-alliance/the-blue-alliance/blob/master/consts/award_type.py#L6
ROBOT_BLUE_BANNER_AWARD_TYPES = (1, 74)
TEAM_BLUE_BANNER_AWARD_TYPES = (0, 3, 69, 80)


class AwardProcessor:

    def __init__(self, n_jobs=4):
        self.cred_manager = CredentialManager()
        tba_api_info = self.cred_manager.get_credential("tba")
        self.tba_api = TheBlueAllianceAPI(tba_api_info['base_url'], tba_api_info['api_key'])

        self.n_jobs = n_jobs

    def process_award_recipients(self, award_recipients, award_type, award_str, event_code):
        for recipient in award_recipients:
            team_key = recipient['team_key']
            team_data = self.tba_api.get_data(f"/team/{team_key}")
            if award_type in ROBOT_BLUE_BANNER_AWARD_TYPES:
                print(f"{award_str} (ROBOT) @ {event_code}: {team_data.json()['team_number']}")
            elif award_type in TEAM_BLUE_BANNER_AWARD_TYPES:
                print(f"{award_str} (TEAM) @ {event_code}: {team_data.json()['team_number']}")

    def process_event(self, event_key: str):
        # Pull this event's data to determine if it is an official event.
        event_data = self.tba_api.get_data(f"/event/{event_key}")
        event_info = event_data.json()

        # If this is an official event, pull all the awards. We only consider the blue banner awards.
        if event_info['event_type'] in VALID_EVENT_TYPES:
            event_award_data = self.tba_api.get_data(f"/event/{event_key}/awards")

            for award_info in event_award_data.json():
                if award_info['award_type'] in TEAM_BLUE_BANNER_AWARD_TYPES or award_info['award_type'] in ROBOT_BLUE_BANNER_AWARD_TYPES:
                    self.process_award_recipients(
                        award_info['recipient_list'], award_info['award_type'], award_info['name'], award_info['event_key'])

    def update_year_awards(self, year):
        # Request all event keys for the given year. This will be used to collect all relevant awards.
        event_keys_result = self.tba_api.get_data(f"/events/{year}/keys")

        # Process the events in parallel
        Parallel(n_jobs=self.n_jobs)(delayed(self.process_event)(key) for key in event_keys_result.json())

    def update_all_awards(self):

        # First year of TBA data for event awards
        start_year = 1992

        # Get the current year
        today = datetime.date.today()
        cur_year = today.year

        # Load all awards for all time up to the current year.
        for year in range(start_year, cur_year+1):
            self.update_year_awards(year)
