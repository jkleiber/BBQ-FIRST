
def compute_bbq_contribution(blue_banners: dict):
        num_banners = len(blue_banners)
        return num_banners

def compute_sauce_contribution(blue_banners: dict, cur_year: int):
    # Note: Any event pre-2005 will have 0 sauce.
    if cur_year < 2005:
        return 0

    sauce_banners = [banner for banner in blue_banners if banner['Event']['year'] >= 2005]
    num_banners = len(sauce_banners)

    return num_banners

def compute_rolling_contribution(blue_banners: dict, cur_year: int, n_seasons: int):
    # Only consider blue banners won by this team at earlier events to this one, and only including the
    # most recent N seasons (plus the current season in progress). For example, a BRIQUETTE (N=4) from 
    # 2024 will include 2024, 2023, 2022, 2021 and 2020 banners.
    min_year = cur_year - n_seasons
    rolling_banners = [banner for banner in blue_banners if banner['Event']['year'] >= min_year]
    num_banners = len(rolling_banners)

    return num_banners