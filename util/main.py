

import argparse

from data_loader import DataLoader

data_loader: DataLoader = None


def load_banners(banner_mode: str, year: int):
    if banner_mode == "since":
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
        pass
    elif team_mode == "full":
        report = data_loader.load_team_info()
        # TODO: load team statistics

    print(report)

def load_events(event_mode: str, year: int):
    if event_mode == "since":
        report = data_loader.load_events_since(year)
    elif event_mode == "year":
        report = data_loader.load_year_events(year)
    elif event_mode == "full":
        report = data_loader.load_all_events()

    print(report)

if __name__ == "__main__":
    # Allow the user to configure the mode and year for this script.
    parser = argparse.ArgumentParser()
    parser.add_argument("mode", default="banner", choices=["banner", "team", "event"])
    parser.add_argument("--banner_mode", default="since", choices=["since", "year", "full"])
    parser.add_argument("--team_mode", default="full", choices=["info", "data", "full"])
    parser.add_argument("--event_mode", default="since", choices=["since", "year", "full"])
    parser.add_argument("--year", default=2024, type=int)
    args = parser.parse_args()

    # Set up the banner loader
    data_loader = DataLoader()

    # Run the appropriate mode.
    if args.mode == "banner":
        load_banners(args.banner_mode, args.year)
    elif args.mode == "team":
        load_teams(args.team_mode)
    elif args.mode == "event":
        load_events(args.event_mode, args.year)

    # The banner factory must be closed or else the supabase process will continue to run and hang the terminal.
    data_loader.close()
