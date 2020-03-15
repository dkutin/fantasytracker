<?php

include_once (__DIR__ . '/constants.php');
include_once (__DIR__ . '/ServiceCall.php');
include_once (__DIR__ . '/helper.php');

/**
 * Class Analytics
 */
class Analytics
{
    private $player_stats;
    /**
     * Analytics constructor.
     */
    function __construct()
    {
        if (file_exists(TMP_DATA_DIR . LEAGUE_KEY . '_free_agents.json') && file_exists(TMP_DATA_DIR . LEAGUE_KEY . '_my_team.json')) {
            $roster = getContents(TMP_DATA_DIR . LEAGUE_KEY . '_my_team.json');
            $free_agents = getContents(TMP_DATA_DIR . LEAGUE_KEY . '_free_agents.json');
            writeToFile(json_encode($roster['team']['roster']['players']), BIN_DIR . 'team_' . TEAM_ID . '_roster.json');
            writeToFile(json_encode($free_agents['league']['players']), BIN_DIR . 'free_agents.json');
            $this->player_stats = [
                'Roster' => $this->createPlayerStats('Roster'),
                'FA' => $this->createPlayerStats('FA'),
            ];
        }
        return $this;
    }

    /**
     * @return array
     */
    function generateSuggestion()
    {
        $player_delta = [
            'FA' => [
                ONE_WEEK_AVG => $this->getPlayerDelta('FA', ONE_WEEK_AVG),
                TWO_WEEK_AVG => $this->getPlayerDelta('FA', TWO_WEEK_AVG),
                ONE_MO_AVG => $this->getPlayerDelta('FA', ONE_MO_AVG),
            ],
            'Roster' => [
                ONE_WEEK_AVG => $this->getPlayerDelta('Roster', ONE_WEEK_AVG),
                TWO_WEEK_AVG => $this->getPlayerDelta('Roster', TWO_WEEK_AVG),
                ONE_MO_AVG => $this->getPlayerDelta('Roster', ONE_MO_AVG),
            ],
        ];

        $player_score = [];

        // TODO: Maybe use central limit theorem here to better rank players
        foreach ($player_delta as $type => $data) {
            foreach ($data as $stat => $week_values) {
                foreach ($week_values as $week => $player_averages) {
                    foreach ($player_averages as $player => $delta) {
                        if (empty($player_score[$type][$player])) {
                            $player_score[$type][$player] = $this->player_stats[$type][$week][$player];
                        }
                        switch ($stat) {
                            case ONE_WEEK_AVG:
                                $player_score[$type][$player] += 1 * $delta;
                                break;
                            case TWO_WEEK_AVG:
                                $player_score[$type][$player] += 1.5 * $delta;
                                break;
                            case ONE_MO_AVG:
                                $player_score[$type][$player] += 3 * $delta;
                                break;
                        }
                    }
                }
            }
        }

        $player_suggestions = [];
        foreach ($player_score['FA'] as $fa_player => $fa_score) {
            foreach ($player_score['Roster'] as $r_player => $r_score) {
                if ($fa_score > $r_score) {
                    $player_suggestions[$r_player][$fa_player] = $fa_score - $r_score;
                }
            }
        }

        return $player_suggestions;
    }

    /**
     * @param $type
     * @param string $player
     * @return array
     */
    function createPlayerStats($type, $player = '') {
        global $scored_stats;
        // If we've specified a player, get their weekly stats (if available)
        if (empty($player)) {
            $files = glob(TMP_DATA_PLAYERS_DIR . "${type}/*.json", GLOB_BRACE);
        } else {
            $files = glob(TMP_DATA_PLAYERS_DIR . "${type}/player_${player}_week_*.json", GLOB_BRACE);
            if (count($files) == 0) {
                print "Not enough data given for player ${player}!";
                return [];
            }
        }

        $players = [];
        foreach ($files as $file) {
            $week = substr($file, -7, 2);
            $data = getContents($file);
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

    function getPlayerDelta($type, $stat, $player = '') {
        if (empty($this->player_stats[$type])) {
            $this->player_stats[$type] = $this->createPlayerStats($type, $player);
        }
        $data = $this->player_stats[$type];
        $stats = [];
        foreach ($data as $week => $players) {
            foreach ($players as $player => $value) {
                if (!empty($data[$week-$stat][$player])) {
                    $stats[$week][$player] = $data[$week][$player] - $data[$week-$stat][$player];
                }
            }
        }
        return $stats;
    }

    function generateReport() {
        $suggestions = $this->generateSuggestion();
        $data = '';
        foreach ($suggestions as $r_player => $fa_players) {
            arsort($fa_players);
            $data .= "<div><p><b>$r_player can be replaced by: </b><br/>";
            foreach ($fa_players as $fa_player => $diff) {
                $data .= "<div>&emsp;&emsp;$fa_player: $diff </div><br/>";
            }
            $data .= "</p></div></br>";
        }
        writeToFile($data, BIN_DIR . 'analysis.csv');
        return $data;
    }
}
