<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Games;

use DillonConstantine\Snap\Cards\Card;

interface GameInterface
{
    public function isMatching(Card $playerCard, ?Card $faceUpCard);
}
