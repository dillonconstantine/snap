<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Services;

use DillonConstantine\Snap\Players\Player;
use DillonConstantine\Snap\Services\SetupService;
use DillonConstantine\Snap\Stacks\Stack;
use DillonConstantine\Snap\Tests\TestCase;

class SetupServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function test_players_are_created_correctly(): void
    {
        $setupService = new SetupService();

        $result = $setupService->createPlayers(['A', 'B', 'C']);

        self::assertInstanceOf(Player::class, $result[0]);
        self::assertSame('A', $result[0]->name);
        self::assertInstanceOf(Stack::class, $result[0]->stack);
        self::assertSame(0, $result[0]->stack->getCount());

        self::assertInstanceOf(Player::class, $result[1]);
        self::assertSame('B', $result[1]->name);
        self::assertInstanceOf(Stack::class, $result[1]->stack);
        self::assertSame(0, $result[1]->stack->getCount());

        self::assertInstanceOf(Player::class, $result[2]);
        self::assertSame('C', $result[2]->name);
        self::assertInstanceOf(Stack::class, $result[2]->stack);
        self::assertSame(0, $result[2]->stack->getCount());
    }

    /**
     * @return array|mixed[]
     */
    public static function deckCountDataProvider(): array
    {
        return [
            '1 Deck'  => [
                'Deck Count' => 1,
            ],
            '2 Decks' => [
                'Deck Count' => 2,
            ],
            '3 Decks' => [
                'Deck Count' => 3,
            ],
            '4 Decks' => [
                'Deck Count' => 4,
            ],
        ];
    }

    /**
     * @dataProvider deckCountDataProvider
     * @param int $deckCount
     * @return void
     */
    public function test_starting_stack_is_created_correctly(int $deckCount): void
    {
        $setupService = new SetupService();

        $result = $setupService->createStartingStack($deckCount);

        self::assertSame(52 * $deckCount, $result->getCount());
    }

    /**
     * @return array|mixed[]
     */
    public static function dealDataProvider(): array
    {
        return [
            'No Cards Remaining' => [
                'Deck Count'                 => 3,
                'Expected Player Card Count' => 52,
                'Expected Remaining Cards'   => 0,
            ],
            'Cards Remaining'    => [
                'Deck Count'                 => 4,
                'Expected Player Card Count' => 69,
                'Expected Remaining Cards'   => 1,
            ],
        ];
    }

    /**
     * @dataProvider dealDataProvider
     * @param int $deckCount
     * @param int $expectedPlayedCardCount
     * @param int $expectedRemainingCards
     * @return void
     */
    public function test_cards_are_dealt_correctly(int $deckCount, int $expectedPlayedCardCount, int $expectedRemainingCards): void
    {
        $playerOne   = new Player('A', new Stack());
        $playerTwo   = new Player('B', new Stack());
        $playerThree = new Player('C', new Stack());

        $players = [$playerOne, $playerTwo, $playerThree];

        $setupService = new SetupService();

        $startingStack = $setupService->createStartingStack($deckCount);

        $setupService->deal($players, $startingStack);

        self::assertSame($expectedPlayedCardCount, $playerOne->stack->getCount());
        self::assertSame($expectedPlayedCardCount, $playerTwo->stack->getCount());
        self::assertSame($expectedPlayedCardCount, $playerThree->stack->getCount());
        self::assertSame($expectedRemainingCards, $startingStack->getCount());
    }
}
