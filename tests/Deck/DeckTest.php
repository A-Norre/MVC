<?php

namespace App\Deck;

use App\Deck\Deck;
use App\Deck\DeckStart;
use App\Deck\Game21;

class DeckTest extends \PHPUnit\Framework\TestCase {

    public function testCards() {
        $deck = new Deck();
        
        $this->assertEquals(52, count($deck->cards()));
    }

    public function testShuffle() {
        $deck = new Deck();
        $deck2 = new Deck();
        $shuffled_deck = $deck->shuffle($deck->cards());
        
        $this->assertNotEquals($deck2->cards(), $shuffled_deck);
    }

    public function testDraw() {
        $deck = new Deck();
        
        $this->assertEquals('2 ♥', $deck->draw($deck->cards()));
    }
}