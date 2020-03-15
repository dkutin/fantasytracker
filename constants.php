<?php

/*
*   Configurable Constants
*/

define('CONSUMER_KEY', '');
define('CONSUMER_SECRET', '');
define('LEAGUE_KEY', '');
define('TEAM_ID', '');
define('FANTASY_TRACKER_EMAIL', '');
define('FANTASY_TRACKER_PASSWORD', '');
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

// Define Directories for referencing
define('BIN_DIR', __DIR__ . '/bin/');
define('TMP_DIR', __DIR__ . '/tmp/');
define('TMP_AUTH_DIR', __DIR__ . '/tmp/auth/');
define('TMP_DATA_DIR', __DIR__ . '/tmp/data/');
define('TMP_DATA_PLAYERS_DIR', __DIR__ . '/tmp/data/players/');

// Define Stat Types
define('ONE_WEEK_AVG', 1);
define('TWO_WEEK_AVG', 2);
define('ONE_MO_AVG', 4);

