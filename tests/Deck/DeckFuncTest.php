<?php

namespace App\Deck;

use App\Deck\Deck;
use App\Deck\DeckStart;
use App\Deck\Game21;

class DeckFuncTest extends \PHPUnit\Framework\TestCase {

    public function testRemove() {
        $deck = new Deck();
        $remaining_cards = $deck->remove($deck->recreate($deck->cards()), $deck->draw($deck->cards()));
        
        $this->assertEquals(51, count($remaining_cards));
    }

    public function testRecreate() {
        $deck = new Deck();
        $deck2 = new Deck();
        
        $this->assertEquals($deck2->cards(), $deck->recreate($deck->cards()));
    }
}