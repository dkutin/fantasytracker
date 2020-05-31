<?php
include_once (__DIR__ . '/class/Analytics.php');

$an = new Analytics(1);
$analysis = $an->generateReport();
$json = array();
foreach ($analysis as $player => $values) {
    $json['players'][] = array(
            'analysis' => $values,
            'info' => getPlayerInfo($player),
        );
}

writeToFile(json_encode($json), __DIR__ . '/../../public/playerData.json');
