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

class BlackjackController extends AbstractController
{
    #[Route('/proj/about', name: "project_about")]
    public function projectBlackJackAbout(): Response
    {
        return $this->render('blackjack/project_about.html.twig');
    }

    #[Route('/proj/about/database', name: "project_about_database")]
    public function projectBlackJackAboutDatabase(): Response
    {
        return $this->render('blackjack/project_about_database.html.twig');
    }

    #[Route('/proj/req6', name: "project_req6")]
    public function projectBlackJackReq6(): Response
    {
        return $this->render('blackjack/project_req6.html.twig');
    }

    #[Route('/blackjack/game', name: "blackjack_game")]
    public function gameBlackJack(): Response
    {
        return $this->render('blackjack/black.html.twig');
    }

    #[Route('/blackjack/bets/{num<\d+>}', name: "blackjack_bets")]
    public function gameBlackJackBets(
        SessionInterface $session,
        int $num 
    ): Response
    {
        session_start();
        session_destroy();

        $session->set("num_players", $num);

        $data = [
            "num_players" => $session->get("num_players"),
        ];
        return $this->render('blackjack/black_bets.html.twig', $data);
    }

    #[Route('/blackjack/shuffle', name: "blackjack_shuffle")]
    public function gameShuffleB(
        SessionInterface $session,
    ): Response
    {
        // session_start();
        // session_destroy();
        $bets1 = $_POST["bets1"];
        $session->set("bets1", $bets1);
        $session->set("playerwon1", false);
        $session->set("playerstop1", false);
        if ($session->get("num_players") >= 2) {
            $bets2 = $_POST["bets2"];
            $session->set("bets2", $bets2);
            $session->set("playerwon2", false);
            $session->set("playerstop2", false);
        }
        if ($session->get("num_players") == 3) {
            $bets3 = $_POST["bets3"];
            $session->set("bets3", $bets3);
            $session->set("playerwon3", false);
            $session->set("playerstop3", false);
        }


        $deck = new Deck();
        $shuffled_deck = $deck->shuffle($deck->cards());

        // $session->set("num_players", $num);
        $session->set("remaining_cards", $shuffled_deck);
        $session->set("first_round", true);
        $session->set("turn", 1);

        return $this->redirectToRoute('blackjack_start');
    }

    #[Route('/blackjack/start', name: "blackjack_start")]
    public function gameStartB(
        SessionInterface $session
    ): Response
    {
        $drawn_player = [];
        $remaining_cards = $session->get("remaining_cards");


        $deck = new Deck();
        $rules = new Game21();
        $remaining_cards = array_values($remaining_cards);

        $player_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_player, $player_card);

        $remaining_cards = array_values($remaining_cards);
        ### round 2 ###

        $player_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_player, $player_card);

        $remaining_cards = array_values($remaining_cards);


        $calc_points = $drawn_player;
        $points_found = 0;


        $sum_points = $rules->checkPointsB($points_found, $calc_points);

        $sum_points = $rules->checkAcesB($sum_points);

        $session->set("drawn_players", $drawn_player);
        $turn = "player" . $session->get("turn");
        $stop = "playerstop" . $session->get("turn");
        $session->set($turn, $drawn_player);
        $session->set($stop, false);
        $session->set("sum_points" . $session->get("turn"), array_sum($sum_points));

        $remaining_cards = array_values($remaining_cards);

        $session->set("remaining_cards", $remaining_cards);

        if ($session->get("sum_points". $session->get("turn")) >= 21 ) {
            $session->set("playerstop" . $session->get("turn"), true);
        }

        if ($session->get("turn") < $session->get("num_players")) {
            $session->set("turn", $session->get("turn")+1); # uppdatera varv
            return $this->redirectToRoute('blackjack_start');
        }



        if ($session->get("playerstop1") == true && $session->get("playerstop2") == true && $session->get("playerstop3") == true) {
            return $this->redirectToRoute('blackjack_bank');
        }

        $data = [
            "player1" => $session->get("player1"),
            "player2" => $session->get("player2"),
            "player3" => $session->get("player3"),
            "num_cards" => count($deck->recreate($remaining_cards)),
            "sum_points1" => $session->get("sum_points1"),
            "sum_points2" => $session->get("sum_points2"),
            "sum_points3" => $session->get("sum_points3"),
            "num_players" => $session->get("num_players"),
            "playerstop1" => $session->get("playerstop1"),
            "playerstop2" => $session->get("playerstop2"),
            "playerstop3" => $session->get("playerstop3"),
            "turn" => $turn,
        ];

        return $this->render('blackjack/black_start.html.twig', $data);
    }

    #[Route('/blackjack/stop/{stop<\d+>}', name: "blackjack_stop")]
    public function gameStopB(
        SessionInterface $session,
        int $stop 
    ): Response
    {

        $session->set("playerstop" . $stop, true);

        return $this->redirectToRoute('blackjack_print');
    }

    #[Route('/blackjack/print', name: "blackjack_print")]
    public function gameShufflePrint(
        SessionInterface $session,
    ): Response
    {

        $remaining_cards = $session->get("remaining_cards");

        $deck = new Deck();
        // $rules = new Game21();

        if ($session->get("turn") == 3) {
            if ($session->get("playerstop1") == true && $session->get("playerstop2") == true && $session->get("playerstop3") == true) {
                return $this->redirectToRoute('blackjack_bank');
            }
        }

        if ($session->get("turn") == 2) {
            if ($session->get("playerstop1") == true && $session->get("playerstop2") == true) {
                return $this->redirectToRoute('blackjack_bank');
            }
        }

        if ($session->get("turn") == 1) {
            if ($session->get("playerstop1") == true) {
                return $this->redirectToRoute('blackjack_bank');
            }
        }
        

        $data = [
            "player1" => $session->get("player1"),
            "player2" => $session->get("player2"),
            "player3" => $session->get("player3"),
            "num_cards" => count($deck->recreate($remaining_cards)),
            "sum_points1" => $session->get("sum_points1"),
            "sum_points2" => $session->get("sum_points2"),
            "sum_points3" => $session->get("sum_points3"),
            "num_players" => $session->get("num_players"),
            "playerstop1" => $session->get("playerstop1"),
            "playerstop2" => $session->get("playerstop2"),
            "playerstop3" => $session->get("playerstop3"),
        ];

        return $this->render('blackjack/black_print.html.twig', $data);
    }

    #[Route('/blackjack/draw/{playernum<\d+>}', name: "blackjack_draw")]
    public function blackJackDraw(
        SessionInterface $session,
        int $playernum
    ): Response
    {
        
        $remaining_cards = $session->get("remaining_cards");

        // $drawn_bank = [];
        $player = "player" . $playernum;

        if ($session->get($player)) {
            $drawn_player = $session->get($player);
            $drawn_player = array_values($drawn_player);
        }

        $deck = new Deck();
        $rules = new Game21();
        $remaining_cards = array_values($remaining_cards);

        $player_card = $deck->draw($remaining_cards);
        $remaining_cards = $deck->remove($deck->recreate($remaining_cards), $deck->draw($remaining_cards));
        array_push($drawn_player, $player_card);

        $remaining_cards = array_values($remaining_cards);

        $calc_points = $drawn_player;
        $points_found = 0;

        $sum_points_player = "sum_points" . $playernum;

        if ($session->get($sum_points_player)) {
            $points_found = $session->get($sum_points_player);
        }

        $sum_points = $rules->checkPointsB($points_found, $calc_points);
        $sum_points = $rules->checkAces($sum_points);

        $session->set("remaining_cards", $remaining_cards);
        $session->set($player, $drawn_player);
        $session->set("sum_points" . $playernum, array_sum($sum_points));
        // $session->set($sum_points_player, $sum_points);

        if ($session->get("sum_points". $playernum) >= 21 ) {
            $session->set("playerstop" . $playernum, true);
        }

        return $this->redirectToRoute('blackjack_print');
    }

    #[Route('/blackjack/bank', name: "blackjack_bank")]
    public function gameBankB(
        SessionInterface $session
    ): Response
    {
        
        $remaining_cards = $session->get("remaining_cards");

        $drawn_bank = [];

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

        $sum_points_bank = $rules->checkPointsB($points_found, $calc_points);
        $sum_points_bank = $rules->checkAces($sum_points_bank);

        $session->set("remaining_cards", $remaining_cards);
        $session->set("drawn_bank", $drawn_bank);
        $session->set("sum_points_bank", $sum_points_bank);


        if (array_sum($sum_points_bank) < 17) {
            return $this->redirectToRoute('blackjack_bank');
        }

        return $this->redirectToRoute('blackjack_over');
    }

    #[Route('/blackjack/over', name: "blackjack_over")]
    public function game_overB(
        SessionInterface $session,
        WinningsRepository $winningsRepository,
        LossesRepository $lossesRepository,
    ): Response
    {
        $player = "player" . $session->get("turn");
        $bets = "bets" . $session->get("turn");
        $sum_points = "sum_points" . $session->get("turn");

        //$drawn_player = $session->get($player);
        $session->set("player" . $session->get("turn"), $session->get($player)); # korten 
        $session->set("sum_points" . $session->get("turn"), $session->get($sum_points)); # poängen


        $points_bank = $session->get("sum_points_bank");
        $drawn_bank = $session->get("drawn_bank");

        $winner = new Game21();
        $find_winner = $winner->totalScoreBlack(array_sum($points_bank), $session->get($sum_points));

        if ($find_winner == "player") {
            $winnings = $winningsRepository->find($session->get("turn"));
            $session->set("playerwon" . $session->get("turn"), true);
            $profit = $session->get($bets) + $winnings->getProfit($session->get("turn"));
            if ($session->get($sum_points) == 21) {
                $session->set($bets, $session->get($bets) * 1.5);
                $winnings->setProfit($profit);
            } else {
                $winnings->setProfit($profit);
            }
            $winningsRepository->save($winnings, true);
        }

        if ($find_winner == "bank") {
            $losses = $lossesRepository->find($session->get("turn"));
            $losses->setLoss($session->get($bets) + $losses->getLoss($session->get("turn")));
            $lossesRepository->save($losses, true);
        }

        ############################## DATABAS UPPÅT ###############


        
        if ($session->get("turn") > 1) {
            $session->set("turn", $session->get("turn")-1);
            return $this->redirectToRoute('blackjack_over');
        }


        $data = [
            "player1" => $session->get("sum_points1"),
            "player_cards1" => $session->get("player1"),
            "player2" => $session->get("sum_points2"),
            "player_cards2" => $session->get("player2"),
            "player3" => $session->get("sum_points3"),
            "player_cards3" => $session->get("player3"),
            "bank" => array_sum($points_bank),
            "bank_cards" => $drawn_bank,
            "num_players" => $session->get("num_players"),
            "bets1" => $session->get("bets1"),
            "bets2" => $session->get("bets2"),
            "bets3" => $session->get("bets3"),
            "playerwon1" => $session->get("playerwon1"),
            "playerwon2" => $session->get("playerwon2"),
            "playerwon3" => $session->get("playerwon3"),
        ];

        return $this->render('blackjack/black_over.html.twig', $data);
    }

    #[Route('/blackjack/database/reset', name: "blackjack_database_reset")]
    public function blackjackDatabaseReset(
        WinningsRepository $winningsRepository,
        LossesRepository $lossesRepository,
    ): Response
    {
        # Spelare 1
        $winnings = $winningsRepository->find(1);
        $winnings->setProfit(0);
        $losses = $lossesRepository->find(1);
        $losses->setLoss(0);
        $winningsRepository->save(/** @scrutinizer ignore-type */ $winnings, true);
        $lossesRepository->save(/** @scrutinizer ignore-type */ $losses, true);

        # Spelare 2
        $winnings = $winningsRepository->find(2);
        $winnings->setProfit(0);
        $losses = $lossesRepository->find(2);
        $losses->setLoss(0);
        $winningsRepository->save(/** @scrutinizer ignore-type */ $winnings, true);
        $lossesRepository->save(/** @scrutinizer ignore-type */ $losses, true);

        # Spelare 3
        $winnings = $winningsRepository->find(3);
        $winnings->setProfit(0);
        $losses = $lossesRepository->find(3);
        $losses->setLoss(0);
        $winningsRepository->save(/** @scrutinizer ignore-type */ $winnings, true);
        $lossesRepository->save(/** @scrutinizer ignore-type */ $losses, true);


        return $this->redirectToRoute('project_about');
    }

}


