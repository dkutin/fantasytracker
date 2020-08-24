# Fantasy Tracker   ![](https://github.com/dkutin/fantasytracker/workflows/Deployment/badge.svg)
Built for NBA Yahoo! Fantasy games to recommend free agents to add to your roster based on historic and recent performance. This application allows customization of tracked stats (for different leagues) and uses Yahoo! Fantasy API for the most up to date data. 

## Motivation
After playing Yahoo! Fantasy Basketball for a few years, I wanted a more intuitive solution that would guarantee my free-agent additions to my roster, and be able to better gauge trades from a statistical point of view. I started developing this app over a year ago, which calls services from Yahoo! Fantasy API, saves data to MySQL database (Since Yahoo! Fantasy does not give access to historical records for the same season...) and run my calculations, then displaying them in a react-app front end. 

## Usage
https://dkutin.github.io/fantasytracker/

Current implementation pulls data from my league, though user log-in feature to be added soon to provide a personalized report for any user playing Yahoo! Fantasy Basketball. 

## Roadmap
- Add API wrapper to call and store data using cloud service
- Support user login for personalized stats on a per-user basis
- Further, develop a statistical model for more accurate recommendations and predictions

## License 

Copyright 2020 Dmitry Kutin

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
