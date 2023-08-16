<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Cards;

enum Suit
{
    case SPADE;
    case CLUB;
    case DIAMOND;
    case HEART;

    /**
     * @return string
     */
    public function toString(): string
    {
        return match ($this) {
            self::SPADE   => '♠',
            self::CLUB    => '♣',
            self::DIAMOND => '♦',
            self::HEART   => '♥',
        };
    }
}
