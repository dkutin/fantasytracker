<?php

/**
 * @param $data
 * @param $filename
 */
function writeToFile($data, $filename)
{
    $fp = fopen($filename, 'w');
    fwrite($fp, $data);
    fclose($fp);
}

/**
 * @param $folder
 */
function deleteAllFromFolder($folder) {
    $files = glob($folder);
    foreach($files as $file){
        if(is_file($file))
            unlink($file);
    }
}

/**
 * @param $filepath
 * @return mixed
 */
function getContents($filepath) {
    if (is_file($filepath)) {
        $data = file_get_contents($filepath);
        return json_decode($data,TRUE);
    }
}

function formatRoster($response) {
    $players = [];
    $i = 0;
    foreach ($response['team']['roster']['players'] as $entry) {
        foreach ($entry as $player) {
            if (!empty($player['player_id'])) {
                $players[$i] = $player['player_id'];
                $i++;
            }
        }
    }
    return $players;
}

function formatFreeAgents($response) {
    $players = [];
    $i = 0;
    foreach ($response['league']['players']['player'] as $player) {
        if (!empty($player['player_id'])) {
            $players[$i] = $player['player_id'];
            $i++;
        }
    }
    return $players;
}

function formatFreeAgentInfo($response) {
    $player_info = [];
    foreach ($response['league']['players']['player'] as $player) {
        $player_info[$player['player_id']] = [
            'full_name' => str_replace("'", '', $player['name']['full']),
            'team' => $player['editorial_team_abbr'],
            'number' => $player['uniform_number'],
            'image' => $player['image_url'],
            'position' => $player['display_position'],
        ];
    }
    return $player_info;
}

function formatOwnedPlayerInfo($response) {
    $player_info = [];
    foreach ($response['team']['roster']['players']['player'] as $player) {
        $player_info[$player['player_id']] = [
            'full_name' => str_replace("'", '', $player['name']['full']),
            'team' => $player['editorial_team_abbr'],
            'number' => $player['uniform_number'],
            'image' => $player['image_url'],
            'position' => $player['display_position'],
        ];
    }
    return $player_info;
}

function formatPlayerStats($response, $scored_stats) {
    $stats = [];
    foreach ($response['player']['player_stats']['stats']['stat'] as $stat_id => $stat) {
        if (array_key_exists($stat['stat_id'], $scored_stats)) {
            switch ($stat['stat_id']){
                case 0:
                    $stats['gp'] = $stat['value'];
                    break;
                case 12:
                    $stats['pts'] = $stat['value'];
                    break;
                case 15:
                    $stats['reb'] = $stat['value'];
                    break;
                case 16:
                    $stats['ast'] = $stat['value'];
                    break;
                case 17:
                    $stats['blk'] = $stat['value'];
                    break;
                case 18:
                    $stats['stl'] = $stat['value'];
                    break;
                case 19:
                    $stats['trn'] = $stat['value'];
                    break;
            }
        }
    }
    return $stats;
}

function formatStats($scored_stats) {
    $stats = [];
    foreach ($scored_stats as $stat_id => $value) {
        switch ($stat_id){
            case 0:
                $stats['gp'] = $value;
                break;
            case 12:
                $stats['pts'] = $value;
                break;
            case 15:
                $stats['reb'] = $value;
                break;
            case 16:
                $stats['ast'] = $value;
                break;
            case 17:
                $stats['blk'] = $value;
                break;
            case 18:
                $stats['stl'] = $value;
                break;
            case 19:
                $stats['trn'] = $value;
                break;
        }
    }
    return $stats;
}


