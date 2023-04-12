<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Deck\Deck;

// kommentar 2

class CardController extends AbstractController
{
    #[Route('/api', name: "api")]
    public function api_all(): Response
    {
    
        return $this->render('api_all.html.twig');
    }

    #[Route('/card', name: "card")]
    public function card_all(): Response
    {
    
        return $this->render('cards_all.html.twig');
    }

    #[Route('/card/deck', name: "see_cards")]
    public function card_deck(): Response
    {
        $deck = new Deck();

        $data = [
            "deck" => $deck->cards(),
        ];

        return $this->render('cards.html.twig', $data);
    }

    #[Route('/card/deck/shuffle', name: "shuffle_card")]
    public function card_shuffle(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $deck = new Deck();
        $shuffled_deck = $deck->shuffle($deck->cards());

        $session->set("remaining_cards", $shuffled_deck);

        $data = [
            "deck" => $shuffled_deck,
        ];

        return $this->render('cards_shuffle.html.twig', $data);
    }

    #[Route('/card/deck/draw', name: "draw_card")]
    public function card_draw(
        Request $request,
        SessionInterface $session
    ): Response
    {
        if ($session->get("remaining_cards")) {

            $remaining_cards = $session->get("remaining_cards");
            $deck = new Deck();
            $remaining_cards = array_values($remaining_cards);
           
            $removed_card = $deck->draw($remaining_cards);
            $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
            
            $session->set("remaining_cards", $remaining_cards);

            $data = [
                "removed" => $removed_card,
                "num_cards" => count($deck->recreate($remaining_cards)),
            ];
            //session_destroy();
        } else {
            $remaining_cards = [];
            $deck = new Deck();
            
            $remaining_cards = $deck->remove($deck->cards(), $deck->draw($deck->cards()));
            $session->set("remaining_cards", $remaining_cards);

            $data = [
                "removed" => $deck->cards()[0],
                "num_cards" => count($deck->recreate($remaining_cards)),
            ];
        }
        
        
        return $this->render('cards_draw.html.twig', $data);

    }


    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_x_card")]
    public function card_draw_spec(
        int $num,
        Request $request,
        SessionInterface $session
    ): Response
    {
        if ($session->get("remaining_cards")) {

            $remaining_cards = $session->get("remaining_cards");
            $deck = new Deck();
            $remaining_cards = array_values($remaining_cards);
            $removed_card = [];

            for ($i = 0; $i < $num; $i++) {
                array_push($removed_card, $deck->draw($remaining_cards, $i));
                $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards, $i));    
            }

            $remaining_cards = array_values($remaining_cards);
            $session->set("remaining_cards", $remaining_cards);

            $data = [
                "removed" => $removed_card,
                "num_cards" => count($deck->recreate($remaining_cards)),
            ];
            //session_destroy();
        } else {
            $remaining_cards = [];
            $deck = new Deck();
            $removed_card = [];
            
            for ($i = 0; $i < $num; $i++) {
                array_push($removed_card, $deck->draw($deck->cards(), $i));
                $remaining_cards = $deck->remove($deck->cards(), $deck->draw($deck->cards(), $i));    
            }
            $session->set("remaining_cards", $remaining_cards);

            $data = [
                "removed" => $removed_card,
                "num_cards" => count($deck->recreate($remaining_cards)),
            ];
        }
        
        return $this->render('cards_draw_spec.html.twig', $data);
    }
}


