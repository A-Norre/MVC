<?php

namespace App\Deck;

use App\Deck\Deck;
use App\Deck\DeckStart;
use App\Deck\Game21;

class DeckStartTest extends \PHPUnit\Framework\TestCase {

    public function testCreateCards() {
		$deck_of_cards = new DeckStart();
        $values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
		$suits  = array(' ♥', ' ♦', ' ♠', ' ♣');
        
        $this->assertContains('K ♠', $deck_of_cards->createcards($values, $suits));
    }
}