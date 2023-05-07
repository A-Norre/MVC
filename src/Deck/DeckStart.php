<?php

namespace App\Deck;

class DeckStart
{
	/**
	 * creates a deck of cards with the values sent in
	 * 
	 */
	public static function createcards($values, $suits)
	{	
		$cards = array();
		foreach ($suits as $suit) {
			foreach ($values as $value) {
				$cards[] = $value . $suit;
			}
		}

		return $cards;
	}
}

