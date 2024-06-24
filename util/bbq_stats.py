
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

def compute_rolling_contribution(blue_banners: dict, cur_year: int, n_seasons: int, cur_week=None, max_week=None):
    # Only consider blue banners won by this team at earlier events to this one, and only including the
    # most recent N seasons (plus the current season in progress). For example, a BRIQUETTE (N=4) from 
    # 2024 will include 2024, 2023, 2022, 2021 and 2020 banners.
    min_year = cur_year - n_seasons
    rolling_banners = []
    if len(blue_banners) > 0:
        rolling_banners = [banner for banner in blue_banners if banner['Event']['year'] >= min_year]
    
    num_banners = len(rolling_banners)

    # Get the banners from this year and the farthest back year allowed to include.
    cur_year_banners = [banner for banner in blue_banners if banner['Event']['year'] == cur_year]
    min_year_banners = [banner for banner in blue_banners if banner['Event']['year'] == min_year]

    # If enough information is available, compute the rolling contribution by fading out the contribution of the 
    # min year and fading in the contribution of the current year.
    if cur_week is not None and max_week is not None:
        if max_week >= cur_week and cur_week > 0:
            cur_year_progress_norm = cur_week / float(max_week)
            cur_year_progress_norm = min(1.0, cur_year_progress_norm)

            # Subtract out the current and minimum years' banners in order to fade them in appropriately.
            num_banners -= len(cur_year_banners)
            num_banners -= len(min_year_banners)

            # Fade in the current year banners and fade out the min year banners.
            # Note: this has the non-intuitive result of creating fractional banners as a season progresses, 
            # but is done to balance out the comparison across event weeks.
            num_banners += cur_year_progress_norm * len(cur_year_banners)
            num_banners += (1 - cur_year_progress_norm) * len(min_year_banners)
        elif max_week < cur_week:
            # If the max week is before the current week, the official season is over. The min year should no longer be incorporated.
            num_banners -= len(min_year_banners)
        
        # If the current week is less than 0, it is the preseason. No adjustments are needed because 
        # the current year's banners are 0.

    return num_banners
