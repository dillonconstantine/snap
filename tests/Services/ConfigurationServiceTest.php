<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Services;

use DillonConstantine\Snap\Services\ConfigurationService;
use DillonConstantine\Snap\Services\DisplayService;
use DillonConstantine\Snap\Tests\TestCase;

class ConfigurationServiceTest extends TestCase
{
    /**
     * @return array|mixed[]
     */
    public static function getVariantDataProvider(): array
    {
        return [
            'Value Provided Is Not Numeric'           => [
                'Value' => 'invalid',
            ],
            'Value Provided Is Numeric, But Too Low'  => [
                'Value' => '0',
            ],
            'Value Provided Is Numeric, But Too High' => [
                'Value' => '3',
            ],
        ];
    }

    /**
     * @dataProvider getVariantDataProvider
     * @param string $value
     * @return void
     */
    public function test_get_variant_functions_correctly(string $value): void
    {
        $displayServiceMock = $this->getMock(DisplayService::class, ['message']);

        $expected = [
            ['Which variant do you want to play?', null],
            ['1) Match Face Only', null],
            ['2) Match Face And Suit', null],
            ['Please provide a valid variant.', DisplayService::COLOR_RED],
            ['Which variant do you want to play?', null],
            ['1) Match Face Only', null],
            ['2) Match Face And Suit', null],
        ];
        $matcher  = self::exactly(7);

        $displayServiceMock->expects($matcher)
            ->method('message')
            ->willReturnCallback(function (string $message, ?int $colour) use ($matcher, $expected) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($expected[0], [$message, $colour]),
                    2 => $this->assertEquals($expected[1], [$message, $colour]),
                    3 => $this->assertEquals($expected[2], [$message, $colour]),
                    4 => $this->assertEquals($expected[3], [$message, $colour]),
                    5 => $this->assertEquals($expected[4], [$message, $colour]),
                    6 => $this->assertEquals($expected[5], [$message, $colour]),
                    7 => $this->assertEquals($expected[6], [$message, $colour]),
                };
            });

        $configurationServiceMock = $this->getMock(ConfigurationService::class, ['readline'], [$displayServiceMock]);
        $configurationServiceMock->expects(self::exactly(2))
            ->method('readline')
            ->with('Please select the variant from the options above: ')
            ->willReturnOnConsecutiveCalls($value, '2');

        // On the first iteration an invalid value is provided.
        // On the second iteration a valid value is provided.
        self::assertSame(2, $configurationServiceMock->getVariant());
    }

    /**
     * @return array|mixed[]
     */
    public static function getDeckCountDataProvider(): array
    {
        return [
            'Value Provided Is Not Numeric'           => [
                'Value' => 'invalid',
            ],
            'Value Provided Is Numeric, But Too Low'  => [
                'Value' => '0',
            ],
            'Value Provided Is Numeric, But Too High' => [
                'Value' => '5',
            ],
        ];
    }

    /**
     * @dataProvider getDeckCountDataProvider
     * @param string $value
     * @return void
     */
    public function test_get_deck_count_functions_correctly(string $value): void
    {
        $displayServiceMock = $this->getMock(DisplayService::class, ['message']);
        $displayServiceMock->expects(self::once())
            ->method('message')
            ->with('Please provide a valid number of decks.', DisplayService::COLOR_RED);

        $configurationServiceMock = $this->getMock(ConfigurationService::class, ['readline'], [$displayServiceMock]);
        $configurationServiceMock->expects(self::exactly(2))
            ->method('readline')
            ->with('How many decks do you want to use (1-4)? ')
            ->willReturnOnConsecutiveCalls($value, '4');

        // On the first iteration an invalid value is provided.
        // On the second iteration a valid value is provided.
        self::assertSame(4, $configurationServiceMock->getDeckCount());
    }

    /**
     * @return array|mixed[]
     */
    public static function getRoundCountDataProvider(): array
    {
        return [
            'Value Provided Is Not Numeric'           => [
                'Value' => 'invalid',
            ],
            'Value Provided Is Numeric, But Too Low'  => [
                'Value' => '0',
            ],
            'Value Provided Is Numeric, But Too High' => [
                'Value' => '101',
            ],
        ];
    }

    /**
     * @dataProvider getRoundCountDataProvider
     * @param string $value
     * @return void
     */
    public function test_get_round_count_functions_correctly(string $value): void
    {
        $displayServiceMock = $this->getMock(DisplayService::class, ['message']);
        $displayServiceMock->expects(self::once())
            ->method('message')
            ->with('Please provide a valid number of rounds.', DisplayService::COLOR_RED);

        $configurationServiceMock = $this->getMock(ConfigurationService::class, ['readline'], [$displayServiceMock]);
        $configurationServiceMock->expects(self::exactly(2))
            ->method('readline')
            ->with('How many rounds do you want the game to last (1-100)? ')
            ->willReturnOnConsecutiveCalls($value, '100');

        // On the first iteration an invalid value is provided.
        // On the second iteration a valid value is provided.
        self::assertSame(100, $configurationServiceMock->getRoundCount());
    }

    /**
     * @return array|mixed[]
     */
    public static function getPlayerCountDataProvider(): array
    {
        return [
            'Value Provided Is Not Numeric'           => [
                'Value' => 'invalid',
            ],
            'Value Provided Is Numeric, But Too Low'  => [
                'Value' => '1',
            ],
            'Value Provided Is Numeric, But Too High' => [
                'Value' => '5',
            ],
        ];
    }

    /**
     * @dataProvider getPlayerCountDataProvider
     * @param string $value
     * @return void
     */
    public function test_get_player_count_functions_correctly(string $value): void
    {
        $displayServiceMock = $this->getMock(DisplayService::class, ['message']);
        $displayServiceMock->expects(self::once())
            ->method('message')
            ->with('Please provide a valid number of players.', DisplayService::COLOR_RED);

        $configurationServiceMock = $this->getMock(ConfigurationService::class, ['readline'], [$displayServiceMock]);
        $configurationServiceMock->expects(self::exactly(2))
            ->method('readline')
            ->with('How many players (2-4)? ')
            ->willReturnOnConsecutiveCalls($value, '4');

        // On the first iteration an invalid value is provided.
        // On the second iteration a valid value is provided.
        self::assertSame(4, $configurationServiceMock->getPlayerCount());
    }

    /**
     * @dataProvider getPlayerCountDataProvider
     * @param string $value
     * @return void
     */
    public function test_get_player_names_functions_correctly(string $value): void
    {
        $displayServiceMock       = $this->getMock(DisplayService::class);
        $configurationServiceMock = $this->getMock(ConfigurationService::class, ['readline'], [$displayServiceMock]);

        $expected = [
            ['Please provide the name of player 1: '],
            ['Please provide the name of player 2: '],
            ['Please provide the name of player 3: '],
        ];
        $matcher  = self::exactly(3);

        $configurationServiceMock->expects(self::exactly(3))
            ->method('readline')
            ->willReturnCallback(function (string $prompt) use ($matcher, $expected) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals($expected[0], [$prompt]),
                    2 => $this->assertEquals($expected[1], [$prompt]),
                    3 => $this->assertEquals($expected[2], [$prompt]),
                };
            })
            ->willReturnOnConsecutiveCalls('A', 'B', 'C');

        // On the first iteration an invalid value is provided.
        // On the second iteration a valid value is provided.
        self::assertSame(['A', 'B', 'C'], $configurationServiceMock->getPlayerNames(3));
    }
}
