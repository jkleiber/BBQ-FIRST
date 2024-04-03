

from supabase import create_client, Client


class SupabaseAPI:

    def __init__(self, supabase_api_info: dict):
        self.supabase_client: Client = create_client(
            supabase_url=supabase_api_info['base_url'], supabase_key=supabase_api_info['api_key'])

        # Log in using the supabase award inserter credentials in order to be able to post updates to the database.
        self.supabase_user = self.supabase_client.auth.sign_in_with_password(
            {"email": supabase_api_info['email'], "password": supabase_api_info['pass']})

    def insert_batch(self, batch: list, table: str):
        # Keep track of the cases that succeed vs fail in order to report to the user if there are problems.
        success_cases = 0
        fail_cases = 0

        for item in batch:
            try:
                self.supabase_client.table(table).insert(item).execute()
                success_cases += 1
            except Exception as e:
                print(f"FAIL: {item}")
                fail_cases += 1

        return {"num_success": success_cases, "num_fail": fail_cases}

    def logout(self):
        res = self.supabase_client.auth.sign_out()
