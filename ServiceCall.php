<?php

include_once(__DIR__ . '/constants.php');
include_once(__DIR__ . '/FantasyAPI.php');
include_once(__DIR__ . '/helper.php');

/**
 * Class ServiceCall
 */
class ServiceCall
{
    /**
     * @var FantasyAPI
     */
    private $api;
    /**
     * @var int
     */
    private $week;

    /**
     * ServiceCall constructor.
     */
    function __construct()
    {
        $this->api = new FantasyAPI();
        if (empty($this->week)) {
            $this->getCurrentWeek();
        }
        return $this;
    }

    /**
     * @return FantasyAPI
     */
    function getFantasyAPI() {
        return $this->api;
    }

    /**
     * @return mixed
     */
    function getCurrentWeek() {
        $current_week = "https://fantasysports.yahooapis.com/fantasy/v2/team/". LEAGUE_KEY . ".t.". TEAM_ID ."/roster";
        $answer = $this->api->makeAPIRequest($current_week)['team']['roster_adds']['coverage_value'];
        $this->week = $answer;
        return $answer;
    }

    /**
     * @return bool|mixed
     */
    function getMyPlayers() {
        $my_team = "https://fantasysports.yahooapis.com/fantasy/v2/team/". LEAGUE_KEY . ".t.". TEAM_ID ."/roster";
        $answer = $this->api->makeAPIRequest($my_team);
        writeToFile(json_encode($answer), TMP_DATA_DIR . LEAGUE_KEY . '_my_team.json');
        return $answer;
    }

    /**
     * @return array
     */
    function getFreeAgents() {
        $answer = [];
        for ($num =0; $num <= FREE_AGENTS_MAX; $num +=25)  {
            $free_agents = "https://fantasysports.yahooapis.com/fantasy/v2/league/". LEAGUE_KEY ."/players;status=FA;start=${num};sort=OR";
            $answer = array_merge_recursive($answer, $this->api->makeAPIRequest($free_agents));
        }
        writeToFile(json_encode($answer), TMP_DATA_DIR . LEAGUE_KEY . '_free_agents.json');
        return $answer;
    }

    /**
     * @param $player_key
     * @param $type
     * @return bool|mixed
     */
    function getPlayerStats($player_key, $type) {
        $week = $this->week;
        $player = "https://fantasysports.yahooapis.com/fantasy/v2/player/395.p.${player_key}/stats";
        $answer = $this->api->makeAPIRequest($player);
        writeToFile(json_encode($answer), TMP_DATA_PLAYERS_DIR . "${type}/player_${player_key}_week_${week}.json");
        return $answer;
    }

    /**
     *
     */
    function getFreeAgentsStats() {
        foreach ($this->getFreeAgents()['league']['players']['player'] as $player) {
            $this->getPlayerStats($player['player_id'], 'FA');
        }
    }

    /**
     *
     */
    function getRosterStats() {
        foreach ($this->getMyPlayers()['team']['roster']['players']['player'] as $player) {
            $this->getPlayerStats($player['player_id'], 'Roster');
        }
    }
}
