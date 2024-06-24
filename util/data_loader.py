
import datetime

from credentials import CredentialManager
from supabase_api import SupabaseAPI
from event_processor import EventProcessor
from tba_api import TheBlueAllianceAPI
from tba_banner_processor import TBABannerProcessor
from team_processor import TeamProcessor

class DataLoader:
    def __init__(self, credentials=None):
        # We store the credentials in a tuple in order to prevent the keys from being unpacked.
        cred_manager = CredentialManager(credentials=credentials[0])
        tba_api_info = cred_manager.get_credential("tba")

        # Initialize the supabase API, used later to submit data to the database.
        supabase_api_info = cred_manager.get_credential("supabase")
        self.supabase_api = SupabaseAPI(supabase_api_info)

        # Initialize the Blue Alliance API, used to pull competition/team/award information.
        self.tba_api = TheBlueAllianceAPI(tba_api_info['base_url'], tba_api_info['api_key'])

        # The banner processor pulls all the relevant banners from TBA.
        self.banner_processor = TBABannerProcessor(self.tba_api, n_jobs=8)
        self.team_processor = TeamProcessor(self.tba_api, self.supabase_api, n_jobs=8)
        self.event_processor = EventProcessor(self.tba_api, self.supabase_api)

        # Get the current year for determining the maximum year for awards/events that have been awarded/completed.
        today = datetime.date.today()
        self.cur_year = today.year

    def __del__(self):
        # Close the supabase connection if this gets destructed. Done to prevent process from hanging the terminal.
        self.close()

    def load_all_banners(self):
        return self.load_banners_since(1992)

    def load_banners_since_current_year(self):
        return self.load_banners_since(self.cur_year)

    def load_banners_since(self, start_year: int):
        # Load all banners for all time up to the current year + 1.
        # Submit data to the database each year and report success/failure.
        report = {}
        for year in range(start_year, self.cur_year+2):
            banner_batch = self.banner_processor.pull_year_banners(year)

            # Submit the results to supabase.
            res = self.supabase_api.insert_batch(banner_batch, "BlueBanner")

            print(f"{year} - # Success: {res['num_success']}, # Fail: {res['num_fail']}")
            report[str(year)] = res

            # Clear the banner queue to avoid repeatedly sending duplicates (that ultimately get rejected by supabase).
            self.banner_processor.clear_banner_queue()

        return report

    def load_year_banners(self, year: int):
        banner_batch = self.banner_processor.pull_year_banners(year)

        # Submit the results to supabase.
        res = self.supabase_api.insert_batch(banner_batch, "BlueBanner")
        return res

    def load_year_events(self, year: int, update_event_data=True):
        res = self.event_processor.load_year_events(year, update_data=update_event_data)
        return res

    def load_events_since(self, start_year: int, update_event_data=True):
        report = []
        # Use cur_year + 2 to be able to capture 1 year in the future (useful in the fall).
        for year in range(start_year, self.cur_year+2):
            print(f"Loading {year} events...")
            year_report = self.load_year_events(year, update_event_data)
            report.append(year_report)

        return report

    def load_all_events(self, update_event_data=True):
        return self.load_events_since(1992, update_event_data)

    def load_events_since_current_year(self, update_event_data=True):
        return self.load_events_since(self.cur_year, update_event_data)

    def load_team_info(self):
        return self.team_processor.load_all_team_info()

    def load_team_data(self):
        # Use event processor to find the current year's maximum week and the corresponding dates.
        self.event_processor.load_year_event_info(self.cur_year)
        start_date_str, _, num_weeks = self.event_processor.get_season_timeline_info(self.cur_year)

        # Compute the current week in the season.
        today = datetime.date.today()

        # There are actually N+1 weeks in the season, because the championship week isn't accounted for in
        # the TBA API.
        num_weeks += 1

        # Compute the current week in the season, defaulting to the end of the season in case of failure.
        cur_week = num_weeks
        if start_date_str is not None:
            # Convert start date to a datetime.
            start_date = datetime.datetime.strptime(start_date_str, "%Y-%m-%d").date()

            # Compute number of weeks elapsed as a float.
            cur_week = (today - start_date) / datetime.timedelta(weeks=1)

        return self.team_processor.load_team_data(cur_week, num_weeks)

    def close(self):
        try:
            # Log out of supabase. If we don't do this, then the program will never end.
            self.supabase_api.logout()
        except Exception as e:
            # HACK: If there's an error, just print the exception and proceed.
            # This is a quick way of allowing the script to close in the event of
            # underlying connections closing prior to the supabase API closing.
            print(e)
