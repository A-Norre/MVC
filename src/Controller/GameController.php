<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Deck\Deck;
use App\Deck\DeckStart;
use App\Deck\DeckCon;

// kommentar 2

class GameController extends AbstractController
{
    #[Route('/game', name: "game")]
    public function game(): Response
    {
        return $this->render('game/game.html.twig');
    }

    #[Route('/game/shuffle', name: "game_shuffle")]
    public function gameShuffle(
        Request $request,
        SessionInterface $session
    ): Response
    {
        session_start();
        session_destroy();

        $deck = new DeckCon();
        $shuffled_deck = $deck->shuffle($deck->cards());

        $session->set("remaining_cards", $shuffled_deck);

        return $this->redirectToRoute('game_start');
    }

    #[Route('/game/start', name: "game_start")]
    public function gameStart(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $drawn_player = [];
        $remaining_cards = $session->get("remaining_cards");

        if ($session->get("drawn_players")) {
            $drawn_player = $session->get("drawn_players");
            $drawn_player = array_values($drawn_player);
        }

        $deck = new DeckCon();
        $remaining_cards = array_values($remaining_cards);

        $player_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_player, $player_card);

        $remaining_cards = array_values($remaining_cards);

        //$banker_card = $deck->draw($remaining_cards);
        //$remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));

        $session->set("remaining_cards", $remaining_cards);
        $session->set("drawn_players", $drawn_player);

        $remaining_cards = array_values($remaining_cards);

        $data = [
            "player" => $drawn_player,
            "num_cards" => count($deck->recreate($remaining_cards)),
        ];

        return $this->render('game/game_start.html.twig', $data);
    }

    #[Route('/game/bank', name: "game_bank")]
    public function gameBank(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $remaining_cards = $session->get("remaining_cards");

        $drawn_bank = [];

        if ($session->get("drawn_bank")) {
            $drawn_bank = $session->get("drawn_bank");
            $drawn_bank = array_values($drawn_bank);
        }

        $deck = new DeckCon();
        $remaining_cards = array_values($remaining_cards);

        $bank_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_bank, $bank_card);



        $remaining_cards = array_values($remaining_cards);

        $session->set("remaining_cards", $remaining_cards);
        $session->set("drawn_bank", $drawn_bank);


        $data = [
            "bank" => $drawn_bank,
            "num_cards" => count($deck->recreate($remaining_cards)),
        ];

        return $this->render('game/game_bank.html.twig', $data);
    }
}


