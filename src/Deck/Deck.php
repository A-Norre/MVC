<?php

namespace App\Deck;

class Deck
{

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

