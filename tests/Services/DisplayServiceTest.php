<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Services;

use DillonConstantine\Snap\Services\DisplayService;
use DillonConstantine\Snap\Tests\TestCase;

class DisplayServiceTest extends TestCase
{
    /**
     * @return array|mixed[]
     */
    public static function messageDataProvider(): array
    {
        return [
            'No Colour Provided' => [
                'Is Color Provided' => false,
                'Expected'          => 'TEST' . PHP_EOL,
            ],
            'Colour Provided'    => [
                'Is Color Provided' => true,
                'Expected'          => "\033[31mTEST\033[0m" . PHP_EOL,
            ],
        ];
    }

    /**
     * @dataProvider messageDataProvider
     * @param bool $isColourProvided
     * @param string $expected
     * @return void
     */
    public function test_message_returns_correct_response(bool $isColourProvided, string $expected): void
    {
        $displayService = new DisplayService();

        $displayService->message('TEST', $isColourProvided ? DisplayService::COLOR_RED : null);

        $this->expectOutputString($expected);
    }

    /**
     * @return void
     */
    public function test_divider_returns_correct_response(): void
    {
        $displayService = new DisplayService();

        $displayService->divider();

        $this->expectOutputString(DisplayService::MESSAGE_DIVIDER . PHP_EOL);
    }
}
