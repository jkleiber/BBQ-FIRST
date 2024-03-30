

import datetime
import requests

from credentials import CredentialManager
from tba_api import TheBlueAllianceAPI


def update_year_awards(year):
    cred_manager = CredentialManager()
    tba_api_info = cred_manager.get_credential("tba")
    tba_api = TheBlueAllianceAPI(tba_api_info['base_url'], tba_api_info['api_key'])

    # Request all event keys for the given year. This will be used to collect all relevant awards.
    event_keys_result = tba_api.get_data(f"/events/{year}/keys")

    # Go through each event to collect important awards. We only consider in-season events.
    for item in event_keys_result.json():
        event_data = tba_api.get_data(f"/event/{item}")
        print(event_data.json())


def update_all_awards():

    # First year of TBA data for event awards
    start_year = 1992

    # Get the current year
    today = datetime.date.today()
    cur_year = today.year

    # Load all awards for all time up to the current year.
    # TODO: parallelize this.
    for year in range(start_year, cur_year+1):
        update_year_awards(year)


if __name__ == "__main__":
    # update_all_awards()
    update_year_awards(1992)