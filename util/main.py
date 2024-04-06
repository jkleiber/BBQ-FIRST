

import argparse

from data_loader import DataLoader

data_loader: DataLoader = None

def update_year(year: int):
    result = data_loader.load_year_banners(year)

def update_since_year(year: int):
    result = data_loader.load_banners_since(year)

def update_all():
    result = data_loader.load_all_banners()

if __name__ == "__main__":
    # Allow the user to configure the mode and year for this script.
    parser = argparse.ArgumentParser()
    parser.add_argument("--mode", default="since", choices=["since", "year", "full", "team_only"])
    parser.add_argument("--year", default=2024, type=int)
    parser.add_argument("--reload_teams", default=False)
    args = parser.parse_args()

    # Set up the banner loader
    data_loader = DataLoader()

    # Load all teams.
    if args.reload_teams:
        data_loader.load_all_teams()

    # Run the appropriate mode.
    if args.mode == "year":
        update_year(args.year)
    elif args.mode == "full":
        update_all()
    elif args.mode == "since":
        update_since_year(args.year)

    # The banner factory must be closed or else the supabase process will continue to run and hang the terminal.
    data_loader.close()
