<?php


// User specific values to be configed before run
define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('LEAGUE_KEY', 'nba.l.');
define('TEAM_ID', '');
define('AUTH_ENDPOINT', 'https://api.login.yahoo.com/oauth2/get_token');
static $scored_stats = [
    '12' => 1,      //PTS
    '15' => 1.2,    //REB
    '16' => 1.5,    //AST
    '17' => 3,      //BLK
    '18' => 3,      //STL
    '19' => -1,     //TO
];
