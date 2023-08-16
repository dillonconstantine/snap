<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Cards;

use DillonConstantine\Snap\Cards\Card;
use DillonConstantine\Snap\Cards\Face;
use DillonConstantine\Snap\Cards\Suit;
use DillonConstantine\Snap\Tests\TestCase;

class CardTest extends TestCase
{
    /**
     * @return void
     */
    public function test_card_is_converted_to_string_correctly(): void
    {
        $card = new Card(Face::ACE, Suit::DIAMOND);

        self::assertSame('A♦', $card->__toString());
    }
}
