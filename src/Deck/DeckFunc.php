<?php

namespace App\Deck;

class DeckFunc
{
	/**
	 * removes a card from the deck
	 * 
	 */
    public static function remove(array $cards, string $draw_card)
	{
		// $removed_card = $draw_card;
		if (in_array($draw_card, $cards))
		{
			unset($cards[array_search($draw_card, $cards)]);
		}
		return $cards;
	}

	/**
	 * recreates a deck of cards
	 * 
	 */
	public function recreate(array $cards)
    {
        return $cards;
    }
}

