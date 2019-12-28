<?php

include_once('constants.php');
include_once('ServiceCall.php');
include_once('helper.php');

class Analytics
{
    function __construct()
    {
        if (file_exists('tmp/data/' . LEAGUE_KEY . '_free_agents.json') && file_exists('tmp/data/' . LEAGUE_KEY . '_my_team.json')) {
            $roster = getContents('tmp/data/' . LEAGUE_KEY . '_my_team.json');
            $free_agents = getContents('tmp/data/' . LEAGUE_KEY . '_free_agents.json');
            writeToFile(json_encode($roster['team']['roster']['players']), 'bin/team_' . TEAM_ID . '_roster.json');
            writeToFile(json_encode($free_agents['league']['players']), 'bin/free_agents.json');
        }
        return $this;
    }

    function generateReport() {

    }

    function setProtectedPlayers($players) {
        $data = getContents('bin/team_' . TEAM_ID . '_roster.json');
        foreach ($data as &$player) {
            if (!empty($player['player_id']) && in_array($player['player_id'], $players)) {
                $player['user_drop_protected'] = "1";
            }
        }
        writeToFile(json_encode($data), 'bin/team/team_' . TEAM_ID . '_roster.json');
    }

    function unsetProtectedPlayers($players) {
        $data = getContents('bin/team_' . TEAM_ID . '_roster.json');
        foreach ($data as &$player) {
            if (!empty($player['player_id']) && in_array($player['player_id'], $players)) {
                unset($player['user_drop_protected']);
            }
        }
        writeToFile(json_encode($data), 'bin/team/team_' . TEAM_ID . '_roster.json');
    }

    function createPlayerAverages() {
        global $scored_stats;
        $files = glob('tmp/data/players/*.json', GLOB_BRACE);
        $players = [];
        foreach ($files as $file) {
            $data = getContents($file);
            $gp = $data['player']['player_stats']['stats']['stat']['0']['value'];
            $averages = [];
            foreach ($data['player']['player_stats']['stats']['stat'] as $stat) {
                if (array_key_exists($stat['stat_id'], $scored_stats)) {
                    $averages[$stat['stat_id']] = ((float)$scored_stats[$stat['stat_id']] * (float)$stat['value']) / (float)$gp;
                }
            }
            $final = array_sum($averages);
            $players[$data['player']['player_id']] = $final;
        }
        arsort($players);
        return $players;
    }



}
