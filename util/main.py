

import argparse
import json
import sys

from data_loader import DataLoader

data_loader: DataLoader = None


def load_banners(banner_mode: str, year: int):
    if banner_mode == "since_current_year":
        result = data_loader.load_banners_since_current_year()
    elif banner_mode == "since":
        result = data_loader.load_banners_since(year)
    elif banner_mode == "year":
        result = data_loader.load_year_banners(year)
    elif banner_mode == "full":
        result = data_loader.load_all_banners()

    print(result)

def load_teams(team_mode):
    if team_mode == "info":
        report = data_loader.load_team_info()
    elif team_mode == "data":
        report = data_loader.load_team_data()
    elif team_mode == "full":
        info_report = data_loader.load_team_info()
        data_report = data_loader.load_team_data()
        report = {
            'info': info_report,
            'data': data_report
        }

    print(report)

def load_events(event_mode: str, year: int, update_event_data: bool):
    if event_mode == "since_current_year":
        report = data_loader.load_events_since_current_year(update_event_data)
    elif event_mode == "since":
        report = data_loader.load_events_since(year, update_event_data)
    elif event_mode == "year":
        report = data_loader.load_year_events(year, update_event_data)
    elif event_mode == "full":
        report = data_loader.load_all_events(update_event_data)

    print(report)

if __name__ == "__main__":
    # Allow the user to configure the mode and year for this script.
    parser = argparse.ArgumentParser()
    parser.add_argument("mode", default="banner", choices=["banner", "team", "event"])
    parser.add_argument("--banner_mode", default="since", choices=["since", "year", "full", "since_current_year"])
    parser.add_argument("--team_mode", default="full", choices=["info", "data", "full"])
    parser.add_argument("--event_mode", default="since", choices=["since", "year", "full", "since_current_year"])
    parser.add_argument("--year", default=2024, type=int)
    parser.add_argument("--info_only", default=False, type=bool)
    parser.add_argument("--credentials", default=None, type=str)
    args = parser.parse_args()

    # If there are credentials provided via the command line, use those instead.
    creds = None
    if args.credentials is not None:
        creds = json.loads(args.credentials)

    # Set up the banner loader.
    data_loader = DataLoader(credentials=(creds,))

    # Run the appropriate mode.
    if args.mode == "banner":
        load_banners(args.banner_mode, args.year)
    elif args.mode == "team":
        load_teams(args.team_mode)
    elif args.mode == "event":
        load_events(args.event_mode, args.year, not args.info_only)

    # The data loader must be closed or else the supabase process will continue to run and hang the terminal.
    # Note: by not setting the auto-refresh token in supabase_api.py, if close() fails the script will still exit.
    data_loader.close()
