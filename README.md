# Fantasy Tracker   ![](https://github.com/dkutin/fantasytracker/workflows/Deployment/badge.svg)
Built for NBA Yahoo! Fantasy games to recommend free agents to add to your roster based on historic and recent performance. This application allows customization of tracked stats (for different leagues) and uses Yahoo! Fantasy API for the most up to date data. 

## Motivation
After playing Yahoo! Fantasy Basketball for a few years, I wanted a more intuitive solution that would guarantee my free-agent additions to my roster, and be able to better gauge trades from a statistical point of view. I started developing this app over a year ago, which calls services from Yahoo! Fantasy API, saves data to MySQL database (Since Yahoo! Fantasy does not give access to historical records for the same season...) and run my calculations, then displaying them in a react-app front end. 

## Usage
https://fantasytracker.dmitrykutin.com
https://dkutin.github.io/fantasytracker

Current implementation pulls data from my league, though user log-in feature to be added soon to provide a personalized report for any user playing Yahoo! Fantasy Basketball. 

## Roadmap
- Add API wrapper to call and store data using cloud service
- Support user login for personalized stats on a per-user basis
- Further, develop a statistical model for more accurate recommendations and predictions

## License 
