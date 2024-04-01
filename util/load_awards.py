

import argparse

from banner_loader import BannerLoader


if __name__ == "__main__":
    # Allow the user to configure the mode and year for this script.
    parser = argparse.ArgumentParser()
    parser.add_argument("--mode", default="year", choices=["year", "full"])
    parser.add_argument("--year", default=2024)
    args = parser.parse_args()

    # Set up the banner loader
    banner_loader = BannerLoader()

    # Run the appropriate mode.
    if args.mode == "year":
        banner_loader.load_year_banners(args.year)
    else:
        banner_loader.load_all_banners()

    # The banner loader must be closed or else the supabase process will continue to run and hang the terminal.
    banner_loader.close()
