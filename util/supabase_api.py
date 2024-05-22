

from supabase import create_client, Client, ClientOptions
from postgrest import SyncSelectRequestBuilder, SyncFilterRequestBuilder
from postgrest.base_request_builder import BaseFilterRequestBuilder
from typing import Union

class SupabaseAPI:

    def __init__(self, supabase_api_info: dict):
        # Setting auto_refresh_token = False ensures the supabase client doesn't hang the script if the program ends.
        self.supabase_client: Client = create_client(
            supabase_url=supabase_api_info['base_url'], supabase_key=supabase_api_info['api_key'], options=ClientOptions(auto_refresh_token=False))

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
    
    def upsert_batch_iterative(self, batch: list, table: str):
        """
        Upsert (UPDATE or INSERT if no row exists) data into a table.
        """
        # Keep track of the cases that succeed vs fail in order to report to the user if there are problems.
        success_cases = 0
        fail_cases = 0

        for item in batch:
            try:
                self.supabase_client.table(table).upsert(item).execute()
                success_cases += 1
            except Exception as e:
                print(f"FAIL: {item}")
                fail_cases += 1

        return {"num_success": success_cases, "num_fail": fail_cases}
    
    def upsert_batch(self, batch: list, table: str):
        """
        Upsert (UPDATE or INSERT if no row exists) data into a table.
        """
        # Keep track if this failed or succeeded.
        success_cases = 0
        fail_cases = 0

        try:
            self.supabase_client.table(table).upsert(batch).execute()
            success_cases += 1
        except Exception as e:
            print(f"Batch Upsert Failed!")
            fail_cases += 1

        return {"num_success": success_cases, "num_fail": fail_cases}
    
    def _filter_process(self, request_builder: BaseFilterRequestBuilder, filter: list):
        for f in filter:
            if f['operation'] == 'eq':
                request_builder = request_builder.eq(f['column'], f['value'])
            elif f['operation'] == 'neq':
                request_builder = request_builder.neq(f['column'], f['value'])
            elif f['operation'] == 'lt':
                request_builder = request_builder.lt(f['column'], f['value'])
            elif f['operation'] == 'lte':
                request_builder = request_builder.lte(f['column'], f['value'])
            elif f['operation'] == 'gt':
                request_builder = request_builder.gt(f['column'], f['value'])
            elif f['operation'] == 'gte':
                request_builder = request_builder.gte(f['column'], f['value'])

    def get_data(self, table: str, columns: str, filter: list):
        request_builder: SyncSelectRequestBuilder = self.supabase_client.table(table).select(columns)
        
        """ Build a request based on the filter values and operators.
        The filter list contains dictionaries set up with the following keys:
        - column: the column to filter on
        - value: the value to use in the filter
        - operation: the type of filter operation (eq, neq, lt, lte)
        """
        self._filter_process(request_builder, filter)

        # Execute request
        data = request_builder.execute()

        return data
    
    def delete_rows(self, table: str, filter: list):
        request_builder: SyncFilterRequestBuilder = self.supabase_client.table(table).delete()
        
        """ Build a request based on the filter values and operators.
        The filter list contains dictionaries set up with the following keys:
        - column: the column to filter on
        - value: the value to use in the filter
        - operation: the type of filter operation (eq, neq, lt, lte)
        """
        self._filter_process(request_builder, filter)

        # Execute request
        data = request_builder.execute()

        return data

    def logout(self):
        res = self.supabase_client.auth.sign_out()
        if res is not None:
            print(res)
