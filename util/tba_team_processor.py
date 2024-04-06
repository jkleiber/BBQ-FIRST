
from tba_api import TheBlueAllianceAPI

class TBATeamProcessor:

    def __init__(self, tba_api: TheBlueAllianceAPI):
        self.tba_api = tba_api

        self.team_queue = []

    def load_teams_from_page(self, page):
        team_page_data = self.tba_api.get_data(f"/teams/{page}")

        for team in team_page_data.json():
            team_info = {
                "nickname": team['nickname'],
                "team_number": team['team_number'],
                "rookie_year": team['rookie_year'],
                "country": team['country'],
                "province": team['state_prov']
            }
            self.team_queue.append(team_info)

        return self.team_queue

    def clear_team_queue(self):
        self.team_queue.clear()