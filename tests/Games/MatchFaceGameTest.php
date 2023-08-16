<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Games;

use DillonConstantine\Snap\Cards\Card;
use DillonConstantine\Snap\Cards\Face;
use DillonConstantine\Snap\Cards\Suit;
use DillonConstantine\Snap\Games\MatchFaceGame;
use DillonConstantine\Snap\Stacks\Stack;
use DillonConstantine\Snap\Tests\TestCase;

class MatchFaceGameTest extends TestCase
{
    /**
     * @return array|mixed[]
     */
    public static function isMatchingDataProvider(): array
    {
        return [
            'Face Does Not Match, Suit Does Not Match' => [
                'Player Card Face'   => Face::JACK,
                'Player Card Suit'   => Suit::CLUB,
                'Face-up Card Face'  => Face::QUEEN,
                'Face-up  Card Suit' => Suit::HEART,
                'Expected'           => false,
            ],
            'Face Does Not Match, Suit Does Match'     => [
                'Player Card Face'   => Face::JACK,
                'Player Card Suit'   => Suit::CLUB,
                'Face-up Card Face'  => Face::QUEEN,
                'Face-up  Card Suit' => Suit::CLUB,
                'Expected'           => false,
            ],
            'No Face-up Card'                          => [
                'Player Card Face'   => Face::JACK,
                'Player Card Suit'   => Suit::CLUB,
                'Face-up Card Face'  => null,
                'Face-up  Card Suit' => null,
                'Expected'           => false,
            ],
            'Face Does Match, Suit Does Not Match'     => [
                'Player Card Face'   => Face::JACK,
                'Player Card Suit'   => Suit::CLUB,
                'Face-up Card Face'  => Face::JACK,
                'Face-up  Card Suit' => Suit::HEART,
                'Expected'           => true,
            ],
            'Face Does Match, Suit Does Match'         => [
                'Player Card Face'   => Face::JACK,
                'Player Card Suit'   => Suit::CLUB,
                'Face-up Card Face'  => Face::JACK,
                'Face-up  Card Suit' => Suit::CLUB,
                'Expected'           => true,
            ],
        ];
    }

    /**
     * @dataProvider isMatchingDataProvider
     * @param Face $playerCardFace
     * @param Suit $playerCardSuit
     * @param Face|null $faceUpCardFace
     * @param Suit|null $faceUpCardSuit
     * @param bool $expected
     * @return void
     */
    public function test_is_match_returns_correct_response(
        Face  $playerCardFace,
        Suit  $playerCardSuit,
        ?Face $faceUpCardFace,
        ?Suit $faceUpCardSuit,
        bool  $expected
    ): void
    {
        $game = new MatchFaceGame([], 1, new Stack());

        $playerCard = new Card($playerCardFace, $playerCardSuit);
        $faceUpCard = $faceUpCardFace !== null && $faceUpCardSuit !== null ?
            new Card($faceUpCardFace, $faceUpCardSuit) : null;

        self::assertSame($expected, $game->isMatching($playerCard, $faceUpCard));
    }
}
