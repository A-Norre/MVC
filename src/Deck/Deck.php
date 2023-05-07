<?php

namespace App\Deck;
use App\Deck\DeckStart;

class Deck extends DeckFunc
{

	/**
	 * assembles a deck of cards
	 * 
	 */
	public static function cards()
	{
		$values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
		$suits  = array(' ♥', ' ♦', ' ♠', ' ♣');
		$deck_of_cards = new DeckStart();

		return $deck_of_cards->createcards($values, $suits);
	}

	/**
	 * shuffles the deck of cards
	 * 
	 */
	public static function shuffle(array $cards)
	{
		$total_cards = count($cards);
		
		foreach ($cards as $i => $card) {
			$card2_i = mt_rand(1, $total_cards) - 1;
			$card2 = $cards[$card2_i];
			
			$cards[$i] = $card2;
			$cards[$card2_i] = $card;
		}
		
		return $cards;
	}

	/**
	 * draws a card from the deck of cards
	 * 
	 */
	public static function draw(array $cards, int $draw_card = 0)
	{		

		return $cards[$draw_card];
	}
}

