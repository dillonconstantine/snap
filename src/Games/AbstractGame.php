<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Games;

use DillonConstantine\Snap\Players\Player;
use DillonConstantine\Snap\Stacks\Stack;

abstract class AbstractGame implements GameInterface
{
    /**
     * @param array|Player[] $players
     * @param int $roundCount
     * @param Stack $stack
     */
    public function __construct(
        public readonly array $players,
        public readonly int   $roundCount,
        public readonly Stack $stack,
    ) {}
}
