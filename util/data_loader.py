
import datetime

from credentials import CredentialManager
from supabase_api import SupabaseAPI
from event_processor import EventProcessor
from tba_api import TheBlueAllianceAPI
from tba_banner_processor import TBABannerProcessor
from tba_team_processor import TBATeamProcessor


class DataLoader:
    def __init__(self):
        cred_manager = CredentialManager()
        tba_api_info = cred_manager.get_credential("tba")

        # Initialize the supabase API, used later to submit data to the database.
        supabase_api_info = cred_manager.get_credential("supabase")
        self.supabase_api = SupabaseAPI(supabase_api_info)

        # Initialize the Blue Alliance API, used to pull competition/team/award information.
        self.tba_api = TheBlueAllianceAPI(tba_api_info['base_url'], tba_api_info['api_key'])

        # The banner processor pulls all the relevant banners from TBA.
        self.banner_processor = TBABannerProcessor(self.tba_api, n_jobs=8, verbose=True)
        self.team_processor = TBATeamProcessor(self.tba_api)
        self.event_processor = EventProcessor(self.tba_api, self.supabase_api)

        # Get the current year for determining the maximum year for awards/events that have been awarded/completed.
        today = datetime.date.today()
        self.cur_year = today.year

    def __del__(self):
        # Close the supabase connection if this gets destructed. Done to prevent process from hanging the terminal.
        self.close()

    def load_all_banners(self):
        return self.load_banners_since(1992)

    def load_banners_since(self, start_year: int):
        # Load all banners for all time up to the current year.
        # Submit data to the database each year and report success/failure.
        report = {}
        for year in range(start_year, self.cur_year+1):
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

    def load_year_events(self, year: int):
        res = self.event_processor.load_year_events(year)
        return res
    
    def load_events_since(self, start_year: int):
        report = []
        for year in range(start_year, self.cur_year+1):
            year_report = self.load_year_events(year)
            report.append(year_report)

        return report
    
    def load_all_events(self):
        self.load_events_since(1992)

    def load_team_info(self):
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
            team_batch = self.team_processor.load_teams_from_page(page_idx)
            if len(team_batch) == 0:
                page_exists = False
                break

            # Push the batch to supabase.
            res = self.supabase_api.upsert_batch(team_batch, "Team")

            # Track information for the report.
            report[str(page_idx)] = res
            most_recent_team = team_batch[-1]['team_number']

            # Clear the team queue for the next run so we aren't submitting stale data.
            self.team_processor.clear_team_queue()
            page_idx += 1

        print(f"Last team: {most_recent_team}")
        print(report)

    def close(self):
        # Log out of supabase. If we don't do this, then the program will never end.
        self.supabase_api.logout()
