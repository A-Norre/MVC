<?php

namespace App\Deck;

class Game21
{
	/**
	 * Compares the total score for bank and player and calculates who the winner is
	 * 
	 */
	public static function totalScore($points_bank, $points_player)
	{
		$winner = "bank";

		if ($points_bank == 21) {
			$winner = "bank";
            return $winner;
        }
        if ($points_player == 21) {
			$winner = "player";
            return $winner;
        }
        if ($points_bank > 21 && $points_player < 21) {
			$winner = "player";
            return $winner;
        }
        if ($points_bank < 21 && $points_player < 21 && $points_bank < $points_player) {
			$winner = "player";
            return $winner;
        }
        
		return $winner;
	}

	/**
	 * Checks if the hand only consists of Aces, and if so recalculates the points
	 * 
	 */
	public static function checkAces($sum_points)
	{
		if (count($sum_points) == 2 && array_sum($sum_points) == 28) {
            $sum_points = [1, 1];
        }
		if (count($sum_points) == 3 && array_sum($sum_points) == 42) {
            $sum_points = [1, 1, 1];
        }
		if (count($sum_points) == 4 && array_sum($sum_points) == 56) {
            $sum_points = [1, 1, 1, 1];
        }
		return $sum_points;
	}


	/**
	 * checks values in hand and calculates total points
	 * 
	 */
	public static function checkPoints($points_found, $calc_points)
	{
		$sum_points = [];
		$lenOfPoints = count($calc_points);

		for ($i = 0; $i < $lenOfPoints; $i++) {
            $temp = strtok($calc_points[$i], ' ');
            if ($temp == 'A') {
                $temp = 1;
                if ($points_found < 8) {
                    $temp = 14;
                }
            }
            if ($temp == 'K') {
                $temp = 13;
            }
            if ($temp == 'Q') {
                $temp = 12;
            }
            if ($temp == 'J') {
                $temp = 11;
            }
            $num = (int)$temp;
            array_push($sum_points, $num);
        }
		return $sum_points;
	}

	public static function checkPointsB($points_found, $calc_points)
	{
		$sum_points = [];
		$lenOfPoints = count($calc_points);

		for ($i = 0; $i < $lenOfPoints; $i++) {
            $temp = strtok($calc_points[$i], ' ');
            if ($temp == 'A') {
                $temp = 11;
                if ($points_found < 8) {
                    $temp = 11;
                }
            }
            if ($temp == 'K' || $temp == 'Q' || $temp == 'J') {
                $temp = 10;
            }
            $num = (int)$temp;
            array_push($sum_points, $num);
        }
		return $sum_points;
	}

    public static function checkAcesB($sum_points)
	{
		if (count($sum_points) == 2 && array_sum($sum_points) == 22) {
            $sum_points = [11, 1];
        }
		return $sum_points;
	}

    public static function totalScoreBlack($points_bank, $points_player)
	{
		$winner = "bank";

		if ($points_bank == 21) {
			$winner = "bank";
            return $winner;
        }
        if ($points_player == 21) {
			$winner = "player";
            return $winner;
        }
        if ($points_bank > 21 && $points_player < 21) {
			$winner = "player";
            return $winner;
        }
        if ($points_bank < 21 && $points_player < 21 && $points_bank < $points_player) {
			$winner = "player";
            return $winner;
        }

        return $winner;
	}
}

