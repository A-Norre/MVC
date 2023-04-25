<?php

namespace App\Deck;

class DeckFunc
{
    public static function remove(array $cards, string $draw_card)
	{
		$removed_card = $draw_card;
		if (in_array($draw_card, $cards))
		{
			unset($cards[array_search($draw_card, $cards)]);
		}
		return $cards;
	}

	public function get_number_cards(array $cards): int
    {
        return count($cards);
    }

	public function recreate(array $cards)
    {
        return $cards;
    }
}

