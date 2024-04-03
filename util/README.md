
# bbq-first-util

These are the utility scripts that pull data for BBQ FIRST.

## Getting Started

To get started, you must have poetry installed to manage the dependencies. Then run `poetry lock` to install/update this package's dependencies.

## Load Awards (load_awards.py)

This script loads all the Blue Banner awards in FRC history. It does this efficiently by multi-threading based on events for a given year.

To run this analysis, execute the following command:
```
poetry run python load_awards.py
```
