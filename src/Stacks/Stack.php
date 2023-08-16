<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Stacks;

use DillonConstantine\Snap\Cards\Card;

class Stack
{
    /**
     * @param array|Card[] $cards
     */
    public function __construct(
        public array $cards = [],
    ) {}

    /**
     * @param Card $card
     * @return array|Card[]
     */
    public function addCard(Card $card): array
    {
        $this->cards[] = $card;

        return $this->cards;
    }

    /**
     * @param array|Card[] $cards
     * @return array|Card[]
     */
    public function addCards(array $cards): array
    {
        // Cards should be added to the bottom of the stack.
        $this->cards = array_merge($cards, $this->cards);

        return $this->cards;
    }

    /**
     * @return Card
     */
    public function getNextCard(): Card
    {
        return array_pop($this->cards);
    }

    /**
     * @return array|Card[]
     */
    public function getAllCards(): array
    {
        $cards = $this->cards;

        $this->cards = [];

        return $cards;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->cards);
    }
}
