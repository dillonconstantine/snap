<?php

declare(strict_types=1);

namespace DillonConstantine\Snap;

use DillonConstantine\Snap\Games\Variant;
use DillonConstantine\Snap\Services\ConfigurationService;
use DillonConstantine\Snap\Services\DisplayService;
use DillonConstantine\Snap\Services\GameService;
use DillonConstantine\Snap\Services\SetupService;

require __DIR__ . '/../vendor/autoload.php';

$displayService       = new DisplayService();
$configurationService = new ConfigurationService($displayService);
$setupService         = new SetupService();
$gameService          = new GameService($displayService);

$displayService->message('Welcome to SNAP!');

// Gather all the required configuration.
$variant     = $configurationService->getVariant();
$deckCount   = $configurationService->getDeckCount();
$roundCount  = $configurationService->getRoundCount();
$playerCount = $configurationService->getPlayerCount();
$playerNames = $configurationService->getPlayerNames($playerCount);

$displayService->divider();

// Create the players using the provided names.
$players = $setupService->createPlayers($playerNames);

// Create the starting stack based on the number of decks requested.
$startingStack = $setupService->createStartingStack($deckCount);

$setupService->deal($players, $startingStack);

// Get the game based on the requested variant.
$gameClass = Variant::from($variant)->getClass();
$game      = new $gameClass($players, $roundCount, $startingStack);

// Simulate the game.
$gameService->play($game);

// Show the results.
$gameService->showResults($game);
