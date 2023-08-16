<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Services;

use DillonConstantine\Snap\Cards\Card;
use DillonConstantine\Snap\Cards\Face;
use DillonConstantine\Snap\Cards\Suit;
use DillonConstantine\Snap\Players\Player;
use DillonConstantine\Snap\Stacks\Stack;

class SetupService
{
    /**
     * @param array|string[] $playerNames
     * @return array|Player[]
     */
    public function createPlayers(array $playerNames): array
    {
        $players = [];
        foreach ($playerNames as $playerName) {
            $players[] = new Player($playerName, new Stack());
        }

        return $players;
    }

    /**
     * @param int $deckCount
     * @return Stack
     */
    public function createStartingStack(int $deckCount): Stack
    {
        $cards = [];
        for ($i = 0; $i < $deckCount; $i++) {
            foreach (Suit::cases() as $suit) {
                foreach (Face::cases() as $face) {
                    $cards[] = new Card($face, $suit);
                }
            }
        }

        shuffle($cards);

        return new Stack($cards);
    }

    /**
     * @param array|Player[] $players
     * @param Stack $startingStack
     * @return void
     */
    public function deal(array $players, Stack $startingStack): void
    {
        // Deal the cards from the starting stack in turn to each player. Each player must have an equal stack.
        // If there are any remaining cards, they will form the initial face-up stack.
        while ($startingStack->getCount() >= count($players)) {
            foreach ($players as $player) {
                $player->stack->addCard($startingStack->getNextCard());
            }
        }
    }
}
