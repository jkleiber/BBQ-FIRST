
import datetime

from credentials import CredentialManager
from supabase_api import SupabaseAPI
from tba_banner_processor import TBABannerProcessor

class BannerLoader:
    def __init__(self):
        cred_manager = CredentialManager()
        tba_api_info = cred_manager.get_credential("tba")

        # Initialize the supabase API, used later to submit data to the database.
        supabase_api_info = cred_manager.get_credential("supabase")
        self.supabase_api = SupabaseAPI(supabase_api_info)

        # The award processor pulls all the relevant awards from TBA.
        self.award_processor = TBABannerProcessor(tba_api_info, n_jobs=8, verbose=True)

    def __del__(self):
        # Close the supabase connection if this gets destructed. Done to prevent process from hanging the terminal.
        self.close()

    def load_all_banners(self):
        return self.load_banners_since(1992)

    def load_banners_since(self, start_year: int):
        # Get the current year
        today = datetime.date.today()
        cur_year = today.year

        # Load all awards for all time up to the current year.
        # Submit data to the database each year and report success/failure.
        report = {}
        for year in range(start_year, cur_year+1):
            award_batch = self.award_processor.pull_year_awards(year)

            # Submit the results to supabase.
            res = self.supabase_api.insert_batch(award_batch, "BlueBanner")

            print(f"{year} - # Success: {res['num_success']}, # Fail: {res['num_fail']}")
            report[str(year)] = res

            # Clear the banner queue to avoid repeatedly sending duplicates (that ultimately get rejected by supabase).
            self.award_processor.clear_banner_queue()

        return report

    def load_year_banners(self, year: int):
        award_batch = self.award_processor.pull_year_awards(year)

        # Submit the results to supabase.
        res = self.supabase_api.insert_batch(award_batch, "BlueBanner")
        return res
    
    def close(self):
        # Log out of supabase. If we don't do this, then the program will never end.
        self.supabase_api.logout()