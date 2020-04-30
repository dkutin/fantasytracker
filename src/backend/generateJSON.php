<?php
include_once (__DIR__ . '/class/Analytics.php');

$an = new Analytics(1);
$analysis = $an->generateReport();
$json = [];
foreach ($analysis as $player => $values) {
    $json[$player]['stats'] = getPlayerStats($player);
    $json[$player]['analysis'] = $values;
    $json[$player]['info'] = getPlayerInfo($player)[0];
}

writeToFile(json_encode($json), __DIR__ . '/../../public/playerData.json');
