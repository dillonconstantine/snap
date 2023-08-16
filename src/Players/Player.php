<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Players;

use DillonConstantine\Snap\Stacks\Stack;

class Player
{
    /**
     * @param string $name
     * @param Stack $stack
     */
    public function __construct(
        public readonly string $name,
        public readonly Stack  $stack,
    ) {}
}
