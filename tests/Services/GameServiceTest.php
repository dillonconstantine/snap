<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Services;

use DillonConstantine\Snap\Cards\Card;
use DillonConstantine\Snap\Cards\Face;
use DillonConstantine\Snap\Cards\Suit;
use DillonConstantine\Snap\Games\MatchFaceGame;
use DillonConstantine\Snap\Players\Player;
use DillonConstantine\Snap\Services\DisplayService;
use DillonConstantine\Snap\Services\GameService;
use DillonConstantine\Snap\Stacks\Stack;
use DillonConstantine\Snap\Tests\TestCase;

class GameServiceTest extends TestCase
{
    /**
     * @return array|mixed[]
     */
    public static function playDataProvider(): array
    {
        return [
            'Does Not Have Initial Face-up Card' => [
                'Has Initial Face-up Card' => false,
            ],
            'Has Initial Face-up Card'           => [
                'Has Initial Face-up Card' => true,
            ],
        ];
    }

    /**
     * @dataProvider playDataProvider
     * @param bool $hasInitialFaceUpCard
     * @return void
     */
    public function test_game_can_be_played_correctly(bool $hasInitialFaceUpCard): void
    {
        $playerOneCardOne   = new Card(Face::EIGHT, Suit::SPADE);
        $playerOneCardTwo   = new Card(Face::ACE, Suit::DIAMOND);
        $playerOneCardThree = new Card(Face::TEN, Suit::CLUB);
        $playerOneCardFour  = new Card(Face::EIGHT, Suit::SPADE);
        $playerOne          = new Player(
            'A',
            new Stack([$playerOneCardOne, $playerOneCardTwo, $playerOneCardThree, $playerOneCardFour]),
        );

        $playerTwoCardOne   = new Card(Face::EIGHT, Suit::SPADE);
        $playerTwoCardTwo   = new Card(Face::JACK, Suit::CLUB);
        $playerTwoCardThree = new Card(Face::TEN, Suit::HEART);
        $playerTwoCardFour  = new Card(Face::ACE, Suit::DIAMOND);
        $playerTwo          = new Player(
            'B',
            new Stack([$playerTwoCardOne, $playerTwoCardTwo, $playerTwoCardThree, $playerTwoCardFour]),
        );

        $players = [$playerOne, $playerTwo];

        $startingFaceUpCard = new Card(Face::TWO, Suit::DIAMOND);

        $game = new MatchFaceGame(
            $players,
            3,
            new Stack($hasInitialFaceUpCard ? [$startingFaceUpCard] : [])
        );

        $displayServiceMock = $this->getMock(DisplayService::class, ['message', 'divider']);

        $expected = [
            [$hasInitialFaceUpCard ? 'Starting face-up card: ' . $startingFaceUpCard : 'Starting face-up card: None'],
            ['Player A: ' . $playerOneCardFour],
            ['Player B: ' . $playerTwoCardFour],
            ['Player A: ' . $playerOneCardThree],
            ['Player B: ' . $playerTwoCardThree],
            ['SNAP! Player B adds 4 cards to their stack!'],
            ['Player A: ' . $playerOneCardTwo],
            ['Player B: ' . $playerTwoCardTwo],
        ];
        $matcher  = self::exactly(8);

        $displayServiceMock->expects($matcher)
            ->method('message')
            ->willReturnCallback(function (string $message) use ($matcher, $expected) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($expected[0], [$message]),
                    2 => $this->assertEquals($expected[1], [$message]),
                    3 => $this->assertEquals($expected[2], [$message]),
                    4 => $this->assertEquals($expected[3], [$message]),
                    5 => $this->assertEquals($expected[4], [$message]),
                    6 => $this->assertEquals($expected[5], [$message]),
                    7 => $this->assertEquals($expected[6], [$message]),
                    8 => $this->assertEquals($expected[7], [$message]),
                };
            });

        $gameServiceMock = $this->getMock(GameService::class, ['showRoundDetails'], [$displayServiceMock]);

        $gameServiceMock->play($game);
    }

    /**
     * @return void
     */
    public function test_game_can_be_played_correctly_when_player_runs_out_of_cards(): void
    {
        $playerOneCardOne = new Card(Face::ACE, Suit::DIAMOND);
        $playerOneCardTwo = new Card(Face::TEN, Suit::CLUB);
        $playerOne        = new Player(
            'A',
            new Stack([$playerOneCardOne, $playerOneCardTwo]),
        );

        $playerTwoCardOne = new Card(Face::JACK, Suit::CLUB);
        $playerTwo        = new Player(
            'B',
            new Stack([$playerTwoCardOne]),
        );

        $players = [$playerOne, $playerTwo];

        $startingFaceUpCard = new Card(Face::TWO, Suit::DIAMOND);

        $game = new MatchFaceGame($players, 2, new Stack([$startingFaceUpCard]));

        $displayServiceMock = $this->getMock(DisplayService::class, ['message', 'divider']);

        $expected = [
            ['Starting face-up card: ' . $startingFaceUpCard],
            ['Player A: ' . $playerOneCardTwo],
            ['Player B: ' . $playerTwoCardOne],
            ['Player B is out of cards!'],
        ];
        $matcher  = self::exactly(4);

        $displayServiceMock->expects($matcher)
            ->method('message')
            ->willReturnCallback(function (string $message) use ($matcher, $expected) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($expected[0], [$message]),
                    2 => $this->assertEquals($expected[1], [$message]),
                    3 => $this->assertEquals($expected[2], [$message]),
                    4 => $this->assertEquals($expected[3], [$message]),
                };
            });

        $gameServiceMock = $this->getMock(GameService::class, ['showRoundDetails'], [$displayServiceMock]);

        $gameServiceMock->play($game);
    }

    /**
     * @return void
     */
    public function test_show_round_details_returns_correct_response(): void
    {
        $playerOne   = new Player(
            'A',
            new Stack([
                new Card(Face::ACE, Suit::DIAMOND),
                new Card(Face::ACE, Suit::CLUB),
            ])
        );
        $playerTwo   = new Player(
            'B',
            new Stack([
                new Card(Face::ACE, Suit::DIAMOND),
            ])
        );
        $playerThree = new Player(
            'C',
            new Stack([
                new Card(Face::ACE, Suit::DIAMOND),
                new Card(Face::ACE, Suit::CLUB),
                new Card(Face::ACE, Suit::HEART),
            ])
        );

        $players = [$playerOne, $playerTwo, $playerThree];

        $game = new MatchFaceGame(
            $players,
            15,
            new Stack([
                new Card(Face::TWO, Suit::DIAMOND),
                new Card(Face::THREE, Suit::CLUB),
                new Card(Face::FOUR, Suit::HEART),
                new Card(Face::FIVE, Suit::DIAMOND),
            ])
        );

        $displayServiceMock = $this->getMock(DisplayService::class, ['message']);

        $expected = [
            ['Round 15 is over!'],
            ['Player A Card Count: 2'],
            ['Player B Card Count: 1'],
            ['Player C Card Count: 3'],
            ['Face-up Card Count: 4'],
        ];
        $matcher  = self::exactly(5);

        $displayServiceMock->expects($matcher)
            ->method('message')
            ->willReturnCallback(function (string $message) use ($matcher, $expected) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($expected[0], [$message]),
                    2 => $this->assertEquals($expected[1], [$message]),
                    3 => $this->assertEquals($expected[2], [$message]),
                    4 => $this->assertEquals($expected[3], [$message]),
                    5 => $this->assertEquals($expected[4], [$message]),
                };
            });

        $gameService = new GameService($displayServiceMock);

        $gameService->showRoundDetails(15, $game);
    }

    /**
     * @return void
     */
    public function test_show_results_returns_correct_response(): void
    {
        $playerOne   = new Player(
            'A',
            new Stack([
                new Card(Face::ACE, Suit::DIAMOND),
                new Card(Face::ACE, Suit::CLUB),
            ])
        );
        $playerTwo   = new Player(
            'B',
            new Stack([
                new Card(Face::ACE, Suit::DIAMOND),
            ])
        );
        $playerThree = new Player(
            'C',
            new Stack([
                new Card(Face::ACE, Suit::DIAMOND),
                new Card(Face::ACE, Suit::CLUB),
                new Card(Face::ACE, Suit::HEART),
            ])
        );

        $players = [$playerOne, $playerTwo, $playerThree];

        $game = new MatchFaceGame($players, 1, new Stack());

        $displayServiceMock = $this->getMock(DisplayService::class, ['message']);

        $expected = [
            ['Results:'],
            ['Player C: 3 Cards'],
            ['Player A: 2 Cards'],
            ['Player B: 1 Cards'],
        ];
        $matcher  = self::exactly(4);

        $displayServiceMock->expects($matcher)
            ->method('message')
            ->willReturnCallback(function (string $message) use ($matcher, $expected) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($expected[0], [$message]),
                    2 => $this->assertEquals($expected[1], [$message]),
                    3 => $this->assertEquals($expected[2], [$message]),
                    4 => $this->assertEquals($expected[3], [$message]),
                };
            });

        $gameService = new GameService($displayServiceMock);

        $gameService->showResults($game);
    }
}
