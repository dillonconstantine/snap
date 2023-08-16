<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Games;

enum Variant: int
{
    case MATCH_FACE_ONLY     = 1;
    case MATCH_FACE_AND_SUIT = 2;

    /**
     * @return string
     */
    public function getName(): string
    {
        return match ($this) {
            self::MATCH_FACE_ONLY     => 'Match Face Only',
            self::MATCH_FACE_AND_SUIT => 'Match Face And Suit',
        };
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return match ($this) {
            self::MATCH_FACE_ONLY     => MatchFaceGame::class,
            self::MATCH_FACE_AND_SUIT => MatchFaceAndSuitGame::class,
        };
    }
}
