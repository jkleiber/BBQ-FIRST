
import math

from supabase import create_client, Client, ClientOptions
from postgrest import SyncSelectRequestBuilder, SyncFilterRequestBuilder
from postgrest.base_request_builder import BaseFilterRequestBuilder, APIResponse
from typing import Union

MAX_ROWS_PER_REQUEST = 1000

class SupabaseAPI:

    def __init__(self, supabase_api_info: dict):
        # Setting auto_refresh_token = False ensures the supabase client doesn't hang the script if the program ends.
        # Setting the postgrest_client_timeout = 30 sets a 30 second timeout, which may be less flaky than the 5 second default.
        self.supabase_client: Client = create_client(
            supabase_url=supabase_api_info['base_url'], 
            supabase_key=supabase_api_info['api_key'], 
            options=ClientOptions(auto_refresh_token=False, postgrest_client_timeout=30))

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
            print(e)
            fail_cases += 1

        return {"num_success": success_cases, "num_fail": fail_cases}
    
    def _filter_process(self, request_builder: BaseFilterRequestBuilder, filter=[], range=None, limit=None, order_info=None):
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

        if range is not None:
            request_builder = request_builder.range(range[0], range[1])
        
        if limit is not None:
            request_builder = request_builder.limit(limit)

        if order_info is not None:
            col = order_info['column']
            desc = order_info['desc']
            request_builder = request_builder.order(col, desc=desc)

    def get_data(self, table: str, columns: str, filter=[], range=None, limit=None, order_info=None) -> APIResponse:
        """ Build a request based on the filter values and operators.
        The filter list contains dictionaries set up with the following keys:
        - column: the column to filter on
        - value: the value to use in the filter
        - operation: the type of filter operation (eq, neq, lt, lte)
        """
        request_builder: SyncSelectRequestBuilder = self.supabase_client.table(table).select(columns)
        self._filter_process(request_builder, filter, range, limit, order_info)

        # Execute request
        data = request_builder.execute()

        return data
    
    def get_paged_data(self, table: str, columns: str, filter=[], order_info=None) -> list[APIResponse]:
        """ Build a request based on the filter values and operators for paged data.
        Supabase limits the number of rows returned, so this function stacks the API 
        responses up into a list for processing by the caller.

        The filter list contains dictionaries set up with the following keys:
        - column: the column to filter on
        - value: the value to use in the filter
        - operation: the type of filter operation (eq, neq, lt, lte)
        """
        # Automatically handle the page limiting of supabase select.
        row_count_response = self.supabase_client.table(table).select(columns, count='exact').execute()
        n_rows = int(row_count_response.model_dump()['count'])
        n_pages = int(math.ceil(n_rows / MAX_ROWS_PER_REQUEST))

        paged_data = []
        for p in range(n_pages):
            start_idx = p * MAX_ROWS_PER_REQUEST
            end_idx = start_idx + (MAX_ROWS_PER_REQUEST - 1)

            data = self.get_data(table, columns, filter, [start_idx, end_idx], order_info=order_info)
            paged_data.append(data)

        return paged_data


    
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
