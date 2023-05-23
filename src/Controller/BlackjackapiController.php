<?php

namespace App\Controller;

use App\Entity\Winnings;
use App\Repository\WinningsRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Losses;
use App\Repository\LossesRepository;

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

class BlackjackapiController extends AbstractController
{
    #[Route('/proj/api', name: "project_api")]
    public function apiBlackJack(
        WinningsRepository $winningsRepository,
    ): Response
    {
        session_start();
        session_destroy();

        return $this->render('blackjack/black_api.html.twig');
    }

    #[Route('/blackjack/api/winnings', name: "blackjack_api_winnings")]
    public function apiBlackJackWinnings(
        WinningsRepository $winningsRepository,
    ): Response {
        $winnings = $winningsRepository->findAll();

        // return $this->json($winnings);
        $response = $this->json($winnings);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/blackjack/api/losses', name: "blackjack_api_losses")]
    public function apiBlackJackLosses(
        LossesRepository $lossesRepository,
    ): Response {
        $losses = $lossesRepository->findAll();

        // return $this->json($losses);
        $response = $this->json($losses);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/blackjack/api/statswinnings', name: "blackjack_api_stats_winnings")]
    public function apiBlackJackStatsWinnings(
        WinningsRepository $winningsRepository,
    ): Response {
        $id = $_POST["id"];
        $winnings = $winningsRepository->find($id);

        // return $this->json($winnings);
        $response = $this->json($winnings);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/blackjack/api/statsloss', name: "blackjack_api_stats_loss")]
    public function apiBlackJackStatsLoss(
        LossesRepository $lossesRepository,
    ): Response {
        $id = $_POST["id"];
        $losses = $lossesRepository->find($id);

        // return $this->json($losses);
        $response = $this->json($losses);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/blackjack/api/self', name: "blackjack_api_self")]
    public function apiBlackJackSelf(
        SessionInterface $session,
        LossesRepository $lossesRepository,
    ): Response {

        $deck = new Deck();
        $rules = new Game21();
        $drawn_player = [];
        $points_found = 0;
        $shuffled_deck = $deck->shuffle($deck->cards());

        $player_card = $deck->draw($shuffled_deck);
        $shuffled_deck = $deck->remove($deck->recreate($shuffled_deck), $deck->draw($shuffled_deck));
        array_push($drawn_player, $player_card);

        $shuffled_deck = array_values($shuffled_deck);

        $sum_points_player = $rules->checkPointsB($points_found, $drawn_player);

        if ($session->get("store_points")) {
            // $session->set("stack", array_push($session->get("stack"), $player_card));
            $stack = $session->get("stack");
            array_push($stack, $drawn_player);
            $session->set("stack", $stack);
            $session->set("store_points", array_sum($sum_points_player) + $session->get("store_points"));
        } else {
            $session->set("store_points", array_sum($sum_points_player));
            $session->set("stack", $drawn_player);
        }


        if ($session->get("store_points") < 17) {
            return $this->redirectToRoute('blackjack_api_self');
        }

        $blackjack = "Du fick inte Blackjack! Försök igen!";

        if ($session->get("store_points") == 21) {
            $blackjack = "Blackjack! Grattis du vann!";
        }


        $data = [
            "Dragna kort: " =>  $session->get("stack"),
            "Poäng: " => $session->get("store_points"),
            "Resultat: " => $blackjack,
        ];
        

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}


