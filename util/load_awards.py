
from award_processor import AwardProcessor

if __name__ == "__main__":
    award_processor = AwardProcessor(n_jobs=8)
    award_processor.update_year_awards(2023)
