

import argparse

from banner_loader import BannerLoader


if __name__ == "__main__":
    # Allow the user to configure the mode and year for this script.
    parser = argparse.ArgumentParser()
    parser.add_argument("--mode", default="since", choices=["since", "year", "full"])
    parser.add_argument("--year", default=2024, type=int)
    args = parser.parse_args()

    # Set up the banner loader
    banner_loader = BannerLoader()

    # Run the appropriate mode.
    if args.mode == "year":
        result = banner_loader.load_year_banners(args.year)
    elif args.mode == "full":
        result = banner_loader.load_all_banners()
    else:
        result = banner_loader.load_banners_since(args.year)

    print(result)

    # The banner loader must be closed or else the supabase process will continue to run and hang the terminal.
    banner_loader.close()
