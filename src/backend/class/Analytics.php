<?php

include_once(__DIR__ . '/ServiceCall.php');
include_once(__DIR__ . '/../helper.php');
include_once(__DIR__ . '/../dbhelper.php');

// Define Stat Types
define('ONE_WEEK_AVG', 1);
define('TWO_WEEK_AVG', 2);
define('ONE_MO_AVG', 4);

/**
 * Class Analytics
 */
class Analytics
{
    private $players;
    private $uid;
    private $stats;

    /**
     * Analytics constructor.
     */
    function __construct($uid)
    {
        $this->uid = $uid;
        $this->players = [
            'Roster' => $this->getRoster(),
            'FreeAgents' => $this->getFreeAgents(),
        ];
        return $this;
    }

    function getRoster() {
        $league = getUserLeague($this->uid);
        $roster = getRoster($league['league_id'], $league['team_id']);
        return unserialize($roster['players']);
    }

    function getFreeAgents() {
        $league = getUserLeague($this->uid);
        $freeagents = getFreeAgents($league['league_id']);
        return unserialize($freeagents['players']);
    }

    function getStats($players) {
        $player_data = [];
        foreach ($players as $player) {
            $player_data[$player] = getPlayerStats($player);
        }
        return $player_data;
    }

    function getScoredStats() {
        $league = getUserLeague($this->uid);
        $scored_stats = getScoredStats($league['league_id']);
        return unserialize($scored_stats['scored_stats']);
    }

    function createPlayerStats() {
        $stats = [];
        $this->stats = [];
        foreach ($this->players as $type => $players) {
            $stats[$type] = $this->getStats($players);
        }
        foreach ($stats as $type => $players) {
            foreach ($players as $index => $values) {
                if (!empty($values)) {
                    $this->stats[$index] = $values;
                }
            }
        }
        return $this->stats;
    }

    function getPlayerDelta($player_data) {
        $player_delta = [];
        foreach ($player_data as $player_id => $data) {
            $num_weeks = sizeof($data);
            if ($num_weeks > ONE_MO_AVG && !empty($data[$num_weeks - 1]) && !empty($data[$num_weeks - 5])) {
                foreach($data[$num_weeks - 1] as $key => $value) {
                    if ($key != 'week') {
                        $player_delta[$player_id][ONE_MO_AVG][$key] = $value - $data[$num_weeks - 5][$key];
                    }
                }
            }
            if ($num_weeks > TWO_WEEK_AVG && !empty($data[$num_weeks - 1]) && !empty($data[$num_weeks - 3])) {
                foreach($data[$num_weeks - 1] as $key => $value) {
                    if ($key != 'week') {
                        $player_delta[$player_id][TWO_WEEK_AVG][$key] = $value - $data[$num_weeks - 3][$key];
                    }
                }
            }
            if ($num_weeks > ONE_WEEK_AVG && !empty($data[$num_weeks - 1]) && !empty($data[$num_weeks - 2])) {
                foreach($data[$num_weeks - 1] as $key => $value) {
                    if ($key != 'week') {
                        $player_delta[$player_id][ONE_WEEK_AVG][$key] = $value - $data[$num_weeks - 2][$key];
                    }
                }
            }

        }
        return $player_delta;
    }


    /**
     * @return array
     */
    function generateSuggestion()
    {
        $player_stats = $this->createPlayerStats();
        $player_delta = $this->getPlayerDelta($player_stats);
        $scored_stats = formatStats($this->getScoredStats());
        $player_score = [];

        // TODO: Maybe use central limit theorem here to better rank players
        foreach ($player_delta as $player_id => $data) {
            foreach ($data as $type => $values) {
                if ($values['gp'] == 0) {
                    $player_score[$player_id][$type] = 0;
                    break;
                }
                $player_score[$player_id][$type] = 0;
                foreach ($scored_stats as $stat_id => $value) {
                    $player_score[$player_id][$type] += $value * $values[$stat_id];
                }
                $player_score[$player_id][$type] = number_format((float)(($player_score[$player_id][$type]) / $values['gp']), 2, '.', '');
            }
        }

        return $player_score;
    }

    function generateReport() {
        $suggestions = $this->generateSuggestion();
        $data = $suggestions;
        setAnalysis($this->uid, json_encode($data));
        return $data;
    }
}
