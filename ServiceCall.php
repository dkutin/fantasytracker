<?php

include_once('constants.php');
include_once('FantasyAPI.php');
include_once('helper.php');

class ServiceCall
{
    private $api;

    function __construct()
    {
        $this->api = new FantasyAPI();
        return $this;
    }

    function getFantasyAPI() {
        return $this->api;
    }

    function getMyPlayers() {
        $my_team = "https://fantasysports.yahooapis.com/fantasy/v2/team/". LEAGUE_KEY . ".t.". TEAM_ID ."/roster";
        $answer = $this->api->makeAPIRequest($my_team);
        writeToFile(json_encode($answer), 'tmp/data/' . LEAGUE_KEY . '_my_team.json');
        return $answer;
    }

    function getFreeAgents() {
        $free_agents = "https://fantasysports.yahooapis.com/fantasy/v2/league/". LEAGUE_KEY ."/players;status=FA;start=0;sort=OR";
        $answer = $this->api->makeAPIRequest($free_agents);
        writeToFile(json_encode($answer), 'tmp/data/' . LEAGUE_KEY . '_free_agents.json');
        return $answer;
    }

    function getPlayerStats($player_key) {
        $player = "https://fantasysports.yahooapis.com/fantasy/v2/player/395.p." . $player_key . "/stats";
        $answer = $this->api->makeAPIRequest($player);
        writeToFile(json_encode($answer), 'tmp/data/players/player_' . $player_key  . '.json');
        return $answer;
    }

    function getMyWeeklyStats() {
        $stats = "https://fantasysports.yahooapis.com/fantasy/v2/team/" . LEAGUE_KEY . ".t." . TEAM_ID . "/stats;type=week;week=9";
        $answer = $this->api->makeAPIRequest($stats);
        writeToFile(json_encode($answer), 'tmp/data/' . LEAGUE_KEY . '_week_' . '.json');
        return $answer;
    }

    function getRosterStats() {
        foreach ($this->getMyPlayers()['team']['roster']['players']['player'] as $player) {
            $this->getPlayerStats($player['player_id']);
        }
    }

    function getFreeAgentsStats() {
        foreach ($this->getFreeAgents()['league']['players']['player'] as $player) {
            $this->getPlayerStats($player['player_id']);
        }
    }

    function getWeeklyPlayerStats($player_id) {
        $player = "https://fantasysports.yahooapis.com/fantasy/v2/league/" . LEAGUE_KEY . "/players;player_keys=395.p." . $player_id . "/stats";
        $answer = $this->api->makeAPIRequest($player);
        writeToFile(json_encode($answer), 'tmp/data/' . LEAGUE_KEY . '_week_' . '.json');
        return $answer;
    }






}
