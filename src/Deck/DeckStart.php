<?php

namespace App\Deck;

class DeckStart
{

	public static function createcards($values, $suits)
	{
		//$values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
		//$suits  = array(' ♠', ' ♥', ' ♦', ' ♣');
		
		$cards = array();
		foreach ($suits as $suit) {
			foreach ($values as $value) {
				$cards[] = $value . $suit;
			}
		}

		return $cards;
	}
}

