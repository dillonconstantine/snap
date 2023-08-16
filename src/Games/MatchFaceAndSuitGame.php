<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Games;

use DillonConstantine\Snap\Cards\Card;

class MatchFaceAndSuitGame extends AbstractGame
{
    public function isMatching(Card $playerCard, ?Card $faceUpCard): bool
    {
        return $playerCard->value === $faceUpCard?->value && $playerCard->suit === $faceUpCard?->suit;
    }
}
