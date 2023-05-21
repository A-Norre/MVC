<?php

namespace App\Deck;

use App\Deck\Deck;
use App\Deck\DeckStart;
use App\Deck\Game21;

class Game21Test extends \PHPUnit\Framework\TestCase {

    public function testTotalScore() {
        $deck = new Game21();
        $decide_winner = $deck->totalScore(21, 20);
        
        $this->assertEquals('bank', $decide_winner);
    }

    public function testTotalScore2() {
        $rules = new Game21();
        $decide_winner = $rules->totalScore(20, 21);
        
        $this->assertEquals('player', $decide_winner);
    }

    public function testTotalScore3() {
        $rules = new Game21();
        $decide_winner = $rules->totalScore(20, 22);
        
        $this->assertEquals('bank', $decide_winner);
    }

    public function testTotalScore4() {
        $rules = new Game21();
        $decide_winner = $rules->totalScore(22, 20);
        
        $this->assertEquals('player', $decide_winner);
    }

    public function testTotalScore5() {
        $rules = new Game21();
        $decide_winner = $rules->totalScore(19, 20);
        
        $this->assertEquals('player', $decide_winner);
    }

    public function testTotalScore6() {
        $rules = new Game21();
        $decide_winner = $rules->totalScore(19, 19);
        
        $this->assertEquals('bank', $decide_winner);
    }

    public function testTotalScore7() {
        $rules = new Game21();
        $decide_winner = $rules->totalScore(19, 18);
        
        $this->assertEquals('bank', $decide_winner);
    }

    public function testCheckPoints() {
        $rules = new Game21();
        // $deck1 = new Deck();
        // $deck2 = new Deck();
        $hand = [];

        array_push($hand, 'A ♥');
        array_push($hand, 'A ♦');

        $sum_points = $rules->checkPoints(10, $hand);
        
        $this->assertEquals([1,1], $sum_points);
    }

    public function testCheckPoints2() {
        $rules = new Game21();
        $hand = [];

        array_push($hand, 'K ♦');
        array_push($hand, 'Q ♥');
        array_push($hand, 'J ♥');

        $sum_points = $rules->checkPoints(10, $hand);
        
        $this->assertEquals([13,12,11], $sum_points);
    }

    public function testCheckAces() {
        $rules = new Game21();
        $hand = [];

        array_push($hand, 'A ♥');
        array_push($hand, 'A ♦');

        $sum_points = $rules->checkPoints(0, $hand);
        $sum_points = $rules->checkAces($sum_points);
        
        $this->assertEquals([1,1], $sum_points);
    }

    public function testCheckAces2() {
        $rules = new Game21();
        $hand = [];

        array_push($hand, 'A ♥');
        array_push($hand, 'A ♦');
        array_push($hand, 'A ♠');
        

        $sum_points = $rules->checkPoints(0, $hand);
        $sum_points = $rules->checkAces($sum_points);
        
        $this->assertEquals([1,1,1], $sum_points);
    }

    public function testCheckAces3() {
        $rules = new Game21();
        $hand = [];

        array_push($hand, 'A ♥');
        array_push($hand, 'A ♦');
        array_push($hand, 'A ♠');
        array_push($hand, 'A ♣');

        $sum_points = $rules->checkPoints(0, $hand);
        $sum_points = $rules->checkAces($sum_points);
        
        $this->assertEquals([1,1,1,1], $sum_points);
    }

    public function testCheckAcesB() {
        $rules = new Game21();
        $hand = [];

        array_push($hand, 'A ♥');
        array_push($hand, 'A ♦');

        $sum_points = $rules->checkPointsB(0, $hand);
        $sum_points = $rules->checkAcesB($sum_points);
        
        $this->assertEquals([11,1], $sum_points);
    }

    public function testCheckpoints3() {
        $rules = new Game21();
        $hand = [];

        array_push($hand, 'K ♥');
        array_push($hand, 'Q ♦');

        $sum_points = $rules->checkPointsB(0, $hand);
        $sum_points = $rules->checkAcesB($sum_points);
        
        $this->assertEquals([10,10], $sum_points);
    }
}