<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Tests\Stacks;

use DillonConstantine\Snap\Cards\Card;
use DillonConstantine\Snap\Cards\Face;
use DillonConstantine\Snap\Cards\Suit;
use DillonConstantine\Snap\Stacks\Stack;
use DillonConstantine\Snap\Tests\TestCase;

class StackTest extends TestCase
{
    /**
     * @return void
     */
    public function test_card_is_added_to_stack_correctly(): void
    {
        $existingCardOne   = new Card(Face::ACE, Suit::DIAMOND);
        $existingCardTwo   = new Card(Face::JACK, Suit::CLUB);
        $existingCardThree = new Card(Face::SEVEN, Suit::HEART);

        $cards = [$existingCardOne, $existingCardTwo, $existingCardThree];

        $stack = new Stack($cards);

        $newCard = new Card(Face::TWO, Suit::SPADE);

        $stack->addCard($newCard);

        self::assertSame([$existingCardOne, $existingCardTwo, $existingCardThree, $newCard], $stack->cards);
    }

    /**
     * @return void
     */
    public function test_cards_are_added_to_stack_correctly(): void
    {
        $existingCardOne   = new Card(Face::ACE, Suit::DIAMOND);
        $existingCardTwo   = new Card(Face::JACK, Suit::CLUB);
        $existingCardThree = new Card(Face::SEVEN, Suit::HEART);

        $cards = [$existingCardOne, $existingCardTwo, $existingCardThree];

        $stack = new Stack($cards);

        $newCardOne = new Card(Face::TWO, Suit::SPADE);
        $newCardTwo = new Card(Face::KING, Suit::CLUB);

        $stack->addCards([$newCardOne, $newCardTwo]);

        self::assertSame([$newCardOne, $newCardTwo, $existingCardOne, $existingCardTwo, $existingCardThree], $stack->cards);
    }

    /**
     * @return void
     */
    public function test_next_card_can_be_fetched_correctly(): void
    {
        $cardOne   = new Card(Face::ACE, Suit::DIAMOND);
        $cardTwo   = new Card(Face::JACK, Suit::CLUB);
        $cardThree = new Card(Face::SEVEN, Suit::HEART);

        $cards = [$cardOne, $cardTwo, $cardThree];

        $stack = new Stack($cards);

        self::assertSame($cardThree, $stack->getNextCard());

        // The card should have been removed from the stack.
        self::assertSame([$cardOne, $cardTwo], $stack->cards);
    }

    /**
     * @return void
     */
    public function test_all_cards_be_fetched_correctly(): void
    {
        $cardOne   = new Card(Face::ACE, Suit::DIAMOND);
        $cardTwo   = new Card(Face::JACK, Suit::CLUB);
        $cardThree = new Card(Face::SEVEN, Suit::HEART);

        $cards = [$cardOne, $cardTwo, $cardThree];

        $stack = new Stack($cards);

        self::assertSame([$cardOne, $cardTwo, $cardThree], $stack->getAllCards());

        // All cards should have been removed from the stack.
        self::assertEmpty($stack->cards);
    }

    /**
     * @return void
     */
    public function test_get_count_returns_correct_response(): void
    {
        $cardOne   = new Card(Face::ACE, Suit::DIAMOND);
        $cardTwo   = new Card(Face::JACK, Suit::CLUB);
        $cardThree = new Card(Face::SEVEN, Suit::HEART);

        $cards = [$cardOne, $cardTwo, $cardThree];

        $stack = new Stack($cards);

        self::assertSame(3, $stack->getCount());
    }
}
