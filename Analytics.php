<?php

include_once('constants.php');
include_once('ServiceCall.php');
include_once('helper.php');

/**
 * Class Analytics
 */
class Analytics
{
    /**
     * Analytics constructor.
     */
    function __construct()
    {
        if (file_exists('tmp/data/' . LEAGUE_KEY . '_free_agents.json') && file_exists('tmp/data/' . LEAGUE_KEY . '_my_team.json')) {
            // TODO: Remove team and free agent files from temp/ every time a new Analytics object is made ...
            //deleteAllFromFolder('tmp/data/*.json');
            $roster = getContents('tmp/data/' . LEAGUE_KEY . '_my_team.json');
            $free_agents = getContents('tmp/data/' . LEAGUE_KEY . '_free_agents.json');
            writeToFile(json_encode($roster['team']['roster']['players']), 'bin/team_' . TEAM_ID . '_roster.json');
            writeToFile(json_encode($free_agents['league']['players']), 'bin/free_agents.json');
        }
        return $this;
    }

    /**
     * @return string
     */
    function generateReport()
    {
        $averages['FA'] = $this->createPlayerAverages('FA');
        $averages['Roster'] = $this->createPlayerAverages('Roster');
        $player_weight = [];
        $data = "";

        foreach ($averages['Roster'] as $rplayer => $rplayer_avg) {
            $free_agents = -1;
            foreach ($averages['FA'] as $fplayer => $fplayer_avg) {
                $free_agents++;
                if ($fplayer_avg >= $rplayer_avg) {
                    $player_weight[$fplayer] = isset($player_weight[$fplayer]) ? $player_weight[$fplayer] + 1 : 1 - $free_agents;
                    $player_weight[$rplayer] = isset($player_weight[$rplayer]) ? $player_weight[$rplayer] - 1 : -1;
                } else {
                    break;
                }
            }
        }

        foreach ($player_weight as $player => $weight) {
            if (array_key_exists($player, $averages['Roster'])) {
                print "You should consider dropping: ${player}. (${weight})" . PHP_EOL;
                $data .= "You should consider dropping: ${player}. (${weight})" . PHP_EOL;
            } else {
                if ($weight > 0) {
                    print "You should consider picking up: ${player}. (${weight})" . PHP_EOL;
                    $data .= "You should consider picking up: ${player}. (${weight})" . PHP_EOL;
                }
            }
        }
        writeToFile(json_encode($player_weight), 'bin/analysis_' . time() . '.json');
        return $data;

    }

    /**
     * @param $type
     * @param string $player
     * @return array
     */
    function createPlayerStats($type, $player = '') {
        // If we've specified a player, get their weekly stats (if available)
        if (empty($player)) {
            $files = glob("tmp/data/players/${type}/*.json", GLOB_BRACE);
        } else {
            $files = glob("tmp/data/players/${type}/player_${player}_week_*.json", GLOB_BRACE);
            if (count($files) < 2) {
                print "Not enough data given for player ${player}!";
                return FALSE;
            }
        }

        $players = [];
        foreach ($files as $file) {
            $week = substr($file, -7, 2);
            $data = getContents($file);
            global $scored_stats;
            $gp = $data['player']['player_stats']['stats']['stat']['0']['value'];
            $averages = [];
            if ($gp > 0) {
                foreach ($data['player']['player_stats']['stats']['stat'] as $stat) {
                    if (array_key_exists($stat['stat_id'], $scored_stats)) {
                        $averages[$stat['stat_id']] = ((float)$scored_stats[$stat['stat_id']] * (float)$stat['value']) / (float)$gp;
                    }
                }
            }
            $players[$week][$data['player']['name']['full']] = array_sum($averages);
        }

        return $players;
    }

    function getSevenDayAverage($type, $player = '') {
        $data = $this->createPlayerStats($type, $player);
        $stats = [];
        foreach ($data as $week => $players) {
            foreach ($players as $player => $value) {
                if (!empty($data[$week-1][$player])) {
                    $stats[$week][$player] = $data[$week][$player] - $data[$week-1][$player];
                }
            }
        }
        return $stats;
    }

    // TODO: Maybe add another parameter to merge 2 functions ...
    function getFourteenDayAverage($type, $player = '') {
        $data = $this->createPlayerStats($type, $player);
        $stats = [];
        foreach ($data as $week => $players) {
            foreach ($players as $player => $value) {
                if (!empty($data[$week-2][$player])) {
                    $stats[$week][$player] = $data[$week][$player] - $data[$week-2][$player];
                }
            }
        }
        return $stats;
    }

}
