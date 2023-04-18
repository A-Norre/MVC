<?php

namespace App\Deck;

class DeckStart extends Deck
{

	public static function cards()
	{
		$values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
		$suits  = array(' ♠', ' ♥', ' ♦', ' ♣');
		
		$cards = array();
		foreach ($suits as $suit) {
			foreach ($values as $value) {
				$cards[] = $value . $suit;
			}
		}

		return $cards;
	}

	public function __construct()
    {
        $this->start_value = null;
    }
}

