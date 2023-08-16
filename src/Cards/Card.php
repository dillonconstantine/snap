<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Cards;

class Card
{
    public function __construct(
        public readonly Face $value,
        public readonly Suit $suit,
    ) {}

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s%s', $this->value->toString(), $this->suit->toString());
    }
}