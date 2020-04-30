<?php

include_once(__DIR__ . '/FantasyAPI.php');
include_once(__DIR__ . '/../helper.php');
include_once(__DIR__ . '/../dbhelper.php');

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

    /*
     * @var int
     */
    private $uid;

    /**
     * ServiceCall constructor.
     * @param $uid
     */
    function __construct($uid)
    {
        $this->uid = $uid;
        $this->api = new FantasyAPI($uid);
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
        if (!$data = getUserLeague($this->uid)) return FALSE;
        $current_week = "https://fantasysports.yahooapis.com/fantasy/v2/team/". $data['league_id'] . ".t.". $data['team_id'] ."/roster";
        $this->week = $this->api->makeAPIRequest($current_week)['team']['roster_adds']['coverage_value'];
        return $this->week;
    }

    function getOwnedPlayers() {
        if (!$league = getUserLeague($this->uid)) return FALSE;
        if (!$num_teams = getNumTeams($league['league_id'])) return FALSE;
        $owned = [];
        for ($i = 1; $i <= $num_teams['num_teams']; $i++) {
            $team = "https://fantasysports.yahooapis.com/fantasy/v2/team/". $league['league_id'] . ".t.". $i ."/roster";
            $answer = $this->api->makeAPIRequest($team);
            $players = formatRoster($answer);
            $owned[$i] = $players;
            $player_info = formatOwnedPlayerInfo($answer);
            foreach ($player_info as $player_id => $values) {
                updatePlayerInfo($player_id, $values);
            }
            updateRoster($league['league_id'], $i, serialize($players));
        }
        return $owned;
    }


    /**
     * @return array | boolean
     */
    function getFreeAgents() {
        if (!$data = getUserLeague($this->uid)) return FALSE;
        $answer = [];
        for ($num = 0; $num <= 50; $num +=25)  {
            $free_agents = "https://fantasysports.yahooapis.com/fantasy/v2/league/${data['league_id']}/players;status=FA;start=${num};sort=OR";
            $answer = array_merge_recursive($answer, $this->api->makeAPIRequest($free_agents));
        }
        $players = formatFreeAgents($answer);
        $player_info = formatFreeAgentInfo($answer);
        foreach ($player_info as $player_id => $values) {
            updatePlayerInfo($player_id, $values);
        }
        updateFreeAgents($data['league_id'], serialize($players));
        return $players;
    }

    /**
     * @param $player_id
     * @return bool|mixed
     */
    function getPlayerStats($player_id) {
        if (!$data = getUserLeague($this->uid)) return FALSE;
        $player = "https://fantasysports.yahooapis.com/fantasy/v2/player/395.p.${player_id}/stats";
        $answer = $this->api->makeAPIRequest($player);
        $scored_stats = unserialize(getScoredStats($data['league_id'])['scored_stats']);
        $data = formatPlayerStats($answer, $scored_stats);
        updatePlayerStats($player_id, $this->week, $data);
        return $data;
    }
}
