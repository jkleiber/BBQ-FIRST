
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
        self.award_processor = TBABannerProcessor(tba_api_info, n_jobs=8)

    def __del__(self):
        # Close the supabase connection if this gets destructed. Done to prevent process from hanging the terminal.
        self.close()

    def load_all_banners(self):
        award_batch = self.award_processor.pull_all_awards()

        # Submit the results to supabase.
        res = self.supabase_api.insert_batch(award_batch, "BlueBanner")
        return res

    def load_year_banners(self, year: int):
        award_batch = self.award_processor.pull_year_awards(year)

        # Submit the results to supabase.
        res = self.supabase_api.insert_batch(award_batch, "BlueBanner")
        return res
    
    def close(self):
        # Log out of supabase. If we don't do this, then the program will never end.
        self.supabase_api.logout()