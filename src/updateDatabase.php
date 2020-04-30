<?php
include (__DIR__ . '/class/ServiceCall.php');
// Open request stream
$sc = new ServiceCall(1);

// Get all free agents
$freeagents = $sc->getFreeAgents();

// Get all owned players
$owned_players = $sc->getOwnedPlayers();

// Get data for all free agents
foreach ($freeagents as $player) {
    $sc->getPlayerStats($player);
}

// Get data for all owned players
foreach ($owned_players as $players) {
    foreach ($players as $player) {
        $sc->getPlayerStats($player);
    }
}
