<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Deck\Deck;
use App\Deck\DeckStart;
//use App\Deck\DeckCon;

class CardControllerJson 
{

    #[Route("/api/deck", name: "api_deck")]
    public function jsonDeck(): Response
    {

        $deck = new Deck();

        $data = [
            "deck" => $deck->cards(),
        ];
        

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_shuffle")]
    public function jsonShuffle(
        SessionInterface $session
    ): Response
    {

        $deck = new Deck();

        $shuffled_deck = $deck->shuffle($deck->cards());

        $session->set("remaining_cards", $shuffled_deck);

        $data = [
            "deck" => $shuffled_deck,
        ];
        

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route('/api/deck/draw', name: "api_draw")]
    public function card_draw(
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
            
            // $remaining_cards = $deck->remove($deck->cards(), $deck->draw($deck->cards()));
            // $session->set("remaining_cards", $remaining_cards);

            $session->set("remaining_cards", $deck->remove($deck->cards(), $deck->draw($deck->cards())));

            $data = [
                "removed" => $deck->cards()[0],
                "num_cards" => count($deck->recreate($remaining_cards)),
            ];
        }
        
        
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;

    }

    #[Route("/api/deck/draw/{num<\d+>}")]
    public function card_draw_spec(
        int $num,
        SessionInterface $session
    ): Response
    {
        if ($session->get("remaining_cards")) {

            $remaining_cards = $session->get("remaining_cards");
            $deck = new Deck();
            $remaining_cards = array_values($remaining_cards);

            if (count($deck->recreate($remaining_cards)) <= 5) {
                //session_destroy();
                //session_start();

                $deck2 = new Deck();
                $remaining_cards = $deck2->shuffle($deck2->cards());
            }

            $removed_card = [];

            for ($i = 0; $i < $num; $i++) {
                array_push($removed_card, $deck->draw($remaining_cards, $i));
                $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards, $i));    
            }

            // $remaining_cards = array_values($remaining_cards);
            // $session->set("remaining_cards", $remaining_cards);
            $session->set("remaining_cards", array_values($remaining_cards));

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
        
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
