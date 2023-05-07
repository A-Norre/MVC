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
use App\Deck\Game21;
//use App\Deck\DeckCon;

// kommentar 2

class GameController extends AbstractController
{
    #[Route('/game', name: "game")]
    public function game(): Response
    {
        return $this->render('game/game.html.twig');
    }

    #[Route('/game/doc', name: "game_doc")]
    public function gameDoc(): Response
    {
        return $this->render('game/game_doc.html.twig');
    }

    #[Route('/game/shuffle', name: "game_shuffle")]
    public function gameShuffle(
        Request $request,
        SessionInterface $session
    ): Response
    {
        // session_start();
        // session_destroy();

        $session->remove("remaining_cards");
        $session->remove("first_round");
        $session->remove("drawn_players");
        $session->remove("sum_points");
        $session->remove("drawn_bank");
        $session->remove("sum_points_bank");

        // $score_player = $session->get("score_player");
        // $score_bank = $session->get("score_bank");

        // $session->set("score_player", 0);
        // $session->set("score_bank", 0);


        $deck = new Deck();
        $shuffled_deck = $deck->shuffle($deck->cards());

        $session->set("remaining_cards", $shuffled_deck);
        $session->set("first_round", true);

        return $this->redirectToRoute('game_start');
    }

    #[Route('/game/start', name: "game_start")]
    public function gameStart(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $drawn_player = [];
        $sum_points = [];
        $remaining_cards = $session->get("remaining_cards");

        if ($session->get("drawn_players")) {
            $drawn_player = $session->get("drawn_players");
            $drawn_player = array_values($drawn_player);
        }

        $deck = new Deck();
        $rules = new Game21();
        $remaining_cards = array_values($remaining_cards);

        $player_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_player, $player_card);

        $remaining_cards = array_values($remaining_cards);

        if ($session->get("first_round") == true) {
            //echo "true";
            $player_card = $deck->draw($remaining_cards);
            $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
            array_push($drawn_player, $player_card);

            $remaining_cards = array_values($remaining_cards);

            $session->set("first_round", false);
        }

        $calc_points = $drawn_player;
        $points_found = 0;

        if ($session->get("sum_points")) {
            $points_found = $session->get("sum_points");
        }

        // for ($i = 0; $i < count($calc_points); $i++) {
        //     $temp = strtok($calc_points[$i], ' ');
        //     if ($temp == 'A') {
        //         $temp = 1;
        //         if ($session->get("sum_points") < 8) {
        //             $temp = 14;
        //         }
        //     }
        //     if ($temp == 'K') {
        //         $temp = 13;
        //     }
        //     if ($temp == 'Q') {
        //         $temp = 12;
        //     }
        //     if ($temp == 'J') {
        //         $temp = 11;
        //     }
        //     $num = (int)$temp;
        //     array_push($sum_points, $num);
        // }

        // if (count($sum_points) == 2 && array_sum($sum_points) == 28) {
        //     $sum_points = [1, 1];
        // }
        $sum_points = $rules->checkPoints($points_found, $calc_points);

        $sum_points = $rules->checkAces($sum_points);

        $session->set("remaining_cards", $remaining_cards);
        $session->set("drawn_players", $drawn_player);
        $session->set("sum_points", array_sum($sum_points));

        $remaining_cards = array_values($remaining_cards);

        

        $data = [
            "player" => $drawn_player,
            "num_cards" => count($deck->recreate($remaining_cards)),
            "sum_points" => array_sum($sum_points),
        ];

        return $this->render('game/game_start.html.twig', $data);
    }

    #[Route('/game/bank', name: "game_bank")]
    public function gameBank(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $bank_stop = false;
        
        $remaining_cards = $session->get("remaining_cards");

        $drawn_bank = [];
        $sum_points_bank = [];
        $remaining_cards = $session->get("remaining_cards");

        if ($session->get("drawn_bank")) {
            $drawn_bank = $session->get("drawn_bank");
            $drawn_bank = array_values($drawn_bank);
        }

        $deck = new Deck();
        $rules = new Game21();
        $remaining_cards = array_values($remaining_cards);

        $player_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_bank, $player_card);

        $remaining_cards = array_values($remaining_cards);

        $calc_points = $drawn_bank;
        $points_found = 0;

        if ($session->get("sum_points_bank")) {
            $points_found = $session->get("sum_points_bank");
        }

        // for ($i = 0; $i < count($calc_points); $i++) {
        //     $temp = strtok($calc_points[$i], ' ');
        //     if ($temp == 'A') {
        //         $temp = 1;
        //         if ($session->get("sum_points_bank") < 8) {
        //             $temp = 14;
        //         }
        //     }
        //     if ($temp == 'K') {
        //         $temp = 13;
        //     }
        //     if ($temp == 'Q') {
        //         $temp = 12;
        //     }
        //     if ($temp == 'J') {
        //         $temp = 11;
        //     }
        //     $num = (int)$temp;
        //     array_push($sum_points_bank, $num);
        // }

        // if (count($sum_points_bank) == 2 && array_sum($sum_points_bank) == 28) {
        //     $sum_points_bank = [1, 1];
        // }
        $sum_points_bank = $rules->checkPoints($points_found, $calc_points);
        $sum_points_bank = $rules->checkAces($sum_points_bank);

        $session->set("remaining_cards", $remaining_cards);
        $session->set("drawn_bank", $drawn_bank);
        $session->set("sum_points_bank", $sum_points_bank);

        $remaining_cards = array_values($remaining_cards);

        if (array_sum($sum_points_bank) < 17) {
            return $this->redirectToRoute('game_bank');
        }

        return $this->redirectToRoute('game_over');
    }

    #[Route('/game/over', name: "game_over")]
    public function game_over(
        Request $request,
        SessionInterface $session
    ): Response
    {
        $points_player = $session->get("sum_points");
        $points_bank = $session->get("sum_points_bank");
        $drawn_player = $session->get("drawn_players");
        $drawn_bank = $session->get("drawn_bank");
        $score_player = 0;
        $score_bank = 0;

        if ($session->get("score_player")) {
            $score_player = $session->get("score_player");
        }
        if ($session->get("score_bank")) {
            $score_bank = $session->get("score_bank");
        }

        $winner = new Game21();
        $find_winner = $winner->totalScore($points_bank, $points_player);

        // $score_bank = (int)$score_bank + 1;
        // $session->set("score_bank", $score_bank);
        // echo $find_winner;

        if ($find_winner === "player") {
            $score_player = (int)$score_player + 1;
            $session->set("score_player", $score_player);
            // $score_bank = (int)$score_bank - 1;
            // $session->set("score_bank", $score_bank);
        }
        if ($find_winner === "bank") {
            $score_bank = (int)$score_bank + 1;
            $session->set("score_bank", $score_bank);
        }
        
        // if ($points_bank == 21) {
        //     $score_bank = (int)$score_bank + 1;
        //     $session->set("score_bank", $score_bank);
        // }
        // elseif ($points_player == 21) {
        //     $score_player = (int)$score_player + 1;
        //     $session->set("score_player", $score_player);
        // }
        // elseif ($points_player > 21 && $points_bank < 21) {
        //     $score_bank = (int)$score_bank + 1;
        //     $session->set("score_bank", $score_bank);
        // }
        // elseif ($points_bank > 21 && $points_player < 21) {
        //     $score_player = (int)$score_player + 1;
        //     $session->set("score_player", $score_player);
        // }
        // elseif ($points_bank < 21 && $points_player < 21 && $points_bank < $points_player) {
        //     $score_player = (int)$score_player + 1;
        //     $session->set("score_player", $score_player);
        // }
        // else {
        //     $score_bank = (int)$score_bank + 1;
        //     $session->set("score_bank", $score_bank);
        // }

        $data = [
            "player" => $points_player,
            "player_cards" => $drawn_player,
            "bank" => array_sum($points_bank),
            "bank_cards" => $drawn_bank,
        ];

        return $this->render('game/game_over.html.twig', $data);
    }

    #[Route("/api/game", name: "api_game")]
    public function jsonGame(
        Request $request,
        SessionInterface $session
    ): Response
    {

        $score_player = $session->get("score_player");
        $score_bank = $session->get("score_bank");

        $data = [
            "Spelare: " =>  $score_player,
            "Bankir: " =>  $score_bank,
        ];
        

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}


