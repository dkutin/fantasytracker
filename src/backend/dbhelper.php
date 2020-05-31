<?php

include(__DIR__ . "/class/Database.php");
$db = new Database();

function getUserCred($uid) {
    global $db;
    return $db->get("SELECT consumer_key, consumer_secret FROM `user` WHERE id = '${uid}'");;
}

function getTokenCred($uid) {
    global $db;
    return $db->get("SELECT access_token, refresh_token, expires, xoauth_yahoo_guid FROM auth WHERE user_id = '${uid}'");;
}

function setTokenCred($access_token, $refresh_token, $expires, $xoauth_yahoo_guid) {
    global $db;
    return $db->put("INSERT IGNORE INTO auth (access_token, refresh_token, expires, xoauth_yahoo_guid) VALUES ('${access_token}', '${refresh_token}', '${expires}', '${xoauth_yahoo_guid}')");
}

function updateTokenCred($uid, $access_token, $refresh_token, $expires, $xoauth_yahoo_guid) {
    global $db;
    return $db->put("UPDATE auth SET access_token = '${access_token}', refresh_token = '${refresh_token}', expires = '${expires}', xoauth_yahoo_guid = '${xoauth_yahoo_guid}' WHERE user_id = '${uid}'");
}

function getUserLeague($uid) {
    global $db;
    return $db->get("SELECT league_id, team_id FROM `user` WHERE id = '${uid}'");
}

function getNumTeams($league_id) {
    global $db;
    return $db->get("SELECT num_teams FROM league WHERE id = '${league_id}'");
}

function setAuth($data) {
    global $db;
    $db->put("INSERT IGNORE INTO `user` (league_id, team_id, consumer_key, consumer_secret) VALUES ()");
}

function updateRoster($league_id, $team_id, $players) {
    global $db;
    $db->put("INSERT INTO team (league_id, team_id, players) VALUES ('${league_id}', '${team_id}', '${players}') ON DUPLICATE KEY UPDATE players = '${players}'");
}

function updateFreeAgents($league_id, $players) {
    global $db;
    $db->put("INSERT INTO freeagents (league_id, players) VALUES ('${league_id}', '${players}') ON DUPLICATE KEY UPDATE players = '${players}'");
}

function getFreeAgents($league_id) {
    global $db;
    return $db->get("SELECT players FROM freeagents WHERE league_id = '${league_id}'");
}
function updatePlayerStats($player_id, $week, $stats) {
    global $db;
    $db->put("INSERT INTO player_data (player_id, week, gp, pts, ast, reb, stl, blk, trn) VALUES ('${player_id}', '${week}', '${stats['gp']}', '${stats['pts']}', '${stats['ast']}', '${stats['reb']}', '${stats['stl']}', '${stats['blk']}', '${stats['trn']}') ON DUPLICATE KEY UPDATE gp = '${stats['gp']}', pts = '${stats['pts']}', ast = '${stats['ast']}', reb = '${stats['reb']}', stl = '${stats['stl']}', blk = '${stats['blk']}', trn = '${stats['trn']}'");
}

function getPlayerStats($player_id) {
    global $db;
    return $db->getMultiple("SELECT week, gp, pts, ast, reb, stl, blk, trn FROM player_data WHERE player_id = '${player_id}'");
}

function getPlayerInfo($player_id) {
    global $db;
    return $db->get("SELECT * FROM player WHERE player_id = '${player_id}'");
}

function getScoredStats($league_id) {
    global $db;
    return $db->get("SELECT scored_stats FROM league WHERE id = '${league_id}'");
}

function setScoredStats($league_id, $scored_stats) {
    global $db;
    $db->put("UPDATE league SET scored_stats = '${scored_stats}' WHERE id='${league_id}'");
}

function getRoster($league_id, $team_id) {
    global $db;
    return $db->get("SELECT players FROM team WHERE league_id = '${league_id}' AND team_id = '${team_id}'");
}

function setAnalysis($uid, $data) {
    global $db;
    $db->put("INSERT INTO analysis VALUES (NULL, '${uid}', NULL, '${data}')");
}

function updatePlayerInfo($player_id, $values) {
    global $db;
    $db->put("INSERT INTO player (player_id, full_name, team, `number`, image, `position`) VALUES ('${player_id}', '${values['full_name']}', '${values['team']}', '${values['number']}', '${values['image']}', '${values['position']}') ON DUPLICATE KEY UPDATE full_name = '${values['full_name']}', team = '${values['team']}', `number` = '${values['number']}', image = '${values['image']}', `position` = '${values['position']}'");
}

