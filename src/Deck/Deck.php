<?php

namespace App\Deck;

class Deck
{

	public static function cards()
	{
		$values = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
		$suits  = array(' Spades', ' Hearts', ' Diamonds', ' Clover');
		
		$cards = array();
		foreach ($suits as $suit) {
			foreach ($values as $value) {
				$cards[] = $value . $suit;
			}
		}

		return $cards;
	}


	public static function shuffle(array $cards)
	{
		$all_cards = count($cards);
		
		foreach ($cards as $i => $card) {
			$card2 = mt_rand(1, $all_cards) - 1;
			$card2 = $cards[$card2];
			
			$cards[$i] = $card2;
			$cards[$card2] = $card;
		}
		
		return $cards;
	}

	public static function draw(array $cards, int $draw_card = 0)
	{		

		return $cards[$draw_card];
	}

	public static function remove(array $cards, string $draw_card)
	{
		$removed_card = $draw_card;
		if (in_array($draw_card, $cards))
		{
			unset($cards[array_search($draw_card, $cards)]);
		}
		return $cards;
	}

	public function getNumberCards(array $cards): int
    {
        return count($cards);
    }

	public function recreate(array $cards)
    {
        return $cards;
    }
}

