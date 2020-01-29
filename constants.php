<?php

/*
*   Configurable Constants
*/

define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('LEAGUE_KEY', '');
define('TEAM_ID', '');
define('FREE_AGENTS_MAX', 50);
static $scored_stats = [
    '12' => 1,      //PTS
    '15' => 1.2,    //REB
    '16' => 1.5,    //AST
    '17' => 3,      //BLK
    '18' => 3,      //STL
    '19' => -1,     //TO
];

/*
 *  Non-Configurable Constants
 */

define('AUTH_ENDPOINT', 'https://api.login.yahoo.com/oauth2/get_token');

