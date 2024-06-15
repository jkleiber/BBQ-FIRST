# BBQ-FIRST

BBQ FIRST was started in 2015 as a way to track FIRST Robotics Competition (FRC) event strength via thematically named statistical metrics. While the metrics themselves are humorous, the data gives insight into the strength of any given event by ranking them against each other using historic team performance.

In lieu of a software architecture document (work in progress): The current website uses the Vue.js framework with Vite as the local development server. The public site is hosted on vercel. The database and any backend services are on supabase. Python is used to periodically update the data, as managed by Github Actions.

## Roadmap

Github milestones are the best way to get visibility into the project roadmap at this time. If you have an idea for a feature, please make a Github issue so it can be added to the appropriate milestone!

## Contributing

Contributions from the community are encouraged! Please remember to follow the code of conduct whenever contributing or interacting with the community.

An example git workflow for contributing can be found [below](#git-workflow).

### Feature Requests
If you have an idea for a new feature of BBQ FIRST, let us know by creating a Github issue. 

While it is not guaranteed that a feature request will be incorporated into the website, we are always looking for great ideas. A feature is far more likely to be prioritized if it:
- Improves the user experience of the site
- Adds value through new insights, predictive analysis, or fun statistics
- Optimizes the data update process
- Adds robustness to the project (automated testing, workflow improvements, CI improvements, etc.)

### Bug Reports
If you find something isn't working, please create a Github issue that includes the steps to reproduce, as well as any evidence/screenshots/print outs that may be helpful in diagnosing the root cause. 

### Git workflow
Things that only need to be done once:
1. Create a fork of this repository.
2. Clone the fork.

Then, the following steps are repeated for each Github issue:  
1. Checkout the `main` branch.  
2. Pull the latest updates.  
3. Create and checkout a new feature branch.  
4. Write code / documentation / etc.  
5. Push code to the fork.  
6. Create a pull request to get the feature into this repository.  

At this point, if the pull request is for a prioritized issue (i.e. it is associated with a Github issue that has a tracked milestone), it will be reviewed by a project admin. If it is not a prioritized issue, it may still be reviewed depending on the project admins availability.

A pull request review is an iterative process. This is an open source project run by volunteers, so patience and gracious professionalism is requested.

## Running Locally

### Website
Hosting the website locally requires a node.js and `npm` installation.

With those prerequisites satisfied, run the following command from your repository root in the terminal to install all the dependencies:
```
npm install
```

Then to host locally in development mode, run
```
npm run dev
```

### Data Update Scripts
Running the data update scripts requires `python` to be installed, and the use of `poetry` as a package manager. Other package managers would work as well, however `poetry` is the chosen package manager / virtual environment for this project.

To perform any update to the database itself, you must have write permissions to the database. These permissions are not being given out for the production database at this time. However, there is planned work for a development database that will have more permissive settings for community members to contribute to.

First, navigate to the `util/` directory and install all dependencies for the python update scripts using 
```
poetry update
```

Running the data update scripts can be done with the following call:
```
poetry run python main.py
```
and then adding in the appropriate mode and configuration settings per the argument parser in `main.py`



