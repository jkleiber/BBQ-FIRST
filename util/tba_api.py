
import requests

class TheBlueAllianceAPI:

    def __init__(self, base_url, api_key):
        self.base_url = base_url
        self.api_key = api_key

    def get_data(self, request_url_suffix):
        return requests.request(method='GET', url=f"{self.base_url}{request_url_suffix}", headers={"X-TBA-Auth-Key": self.api_key})