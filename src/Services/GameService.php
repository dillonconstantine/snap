<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Services;

use DillonConstantine\Snap\Games\AbstractGame;

class GameService
{
    /**
     * @param DisplayService $displayService
     */
    public function __construct(
        private readonly DisplayService $displayService,
    ) {}

    /**
     * @param AbstractGame $game
     * @return void
     */
    public function play(AbstractGame $game): void
    {
        // Get the initial face-up card. This can be null if there were no remaining cards after dealing.
        $faceUpCard   = $game->stack->getCount() !== 0 ? $game->stack->getNextCard() : null;
        $currentRound = 1;

        $this->displayService->message(sprintf('Starting face-up card: %s', $faceUpCard?->__toString() ?? 'None'));

        do {
            foreach ($game->players as $player) {
                $playerNextCard = $player->stack->getNextCard();

                $this->displayService->message(sprintf('Player %s: %s', $player->name, $playerNextCard));

                // If the players next card matches the face-up card, add all face-up cards to the players stack.
                // Otherwise, add the players card to the face-up stack.
                if ($game->isMatching($playerNextCard, $faceUpCard)) {
                    // After adding the players card to the face-up pile, move all face-up cards to the players stack.
                    $game->stack->addCard($playerNextCard);
                    $this->displayService->message(sprintf('SNAP! Player %s adds %d cards to their stack!', $player->name, $game->stack->getCount()), DisplayService::COLOR_GREEN);
                    $player->stack->addCards($game->stack->getAllCards());

                    // There is now no face-up card.
                    $faceUpCard = null;
                } else {
                    $game->stack->addCard($playerNextCard);
                    $faceUpCard = $playerNextCard;
                }

                // If a player is out of cards after their turn, the game is over.
                if ($player->stack->getCount() === 0) {
                    $this->displayService->message(sprintf('Player %s is out of cards!', $player->name), DisplayService::COLOR_GREEN);
                    $this->displayService->divider();

                    return;
                }
            }

            $this->showRoundDetails($currentRound, $game);
            $this->displayService->divider();

            $currentRound++;
        } while ($currentRound <= $game->roundCount);
    }

    /**
     * @param int $currentRound
     * @param AbstractGame $game
     * @return void
     */
    public function showRoundDetails(int $currentRound, AbstractGame $game): void
    {
        $this->displayService->message(sprintf('Round %d is over!', $currentRound));

        // For each player, show their current card count.
        foreach ($game->players as $player) {
            $this->displayService->message(sprintf('Player %s Card Count: %d', $player->name, $player->stack->getCount()));
        }

        $this->displayService->message(sprintf('Face-up Card Count: %d', $game->stack->getCount()));
    }

    /**
     * @param AbstractGame $game
     * @return void
     */
    public function showResults(AbstractGame $game): void
    {
        $standings = [];
        foreach ($game->players as $player) {
            $standings[] = [
                'name'  => $player->name,
                'count' => $player->stack->getCount()
            ];
        }

        // Sort the array by the players card count descending.
        $counts = array_column($standings, 'count');
        array_multisort($counts, SORT_DESC, $standings);

        $this->displayService->message('Results:');
        foreach ($standings as $standing) {
            $this->displayService->message(sprintf('Player %s: %d Cards', $standing['name'], $standing['count']));
        }
    }
}