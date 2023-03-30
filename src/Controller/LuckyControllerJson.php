<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson 
{
    #[Route("/api/lucky/number")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        $number = random_int(0, 2);
        $todays_date = date("Y-m-d");
        $todays_time = date("h:i:s");
        if ($number == 0) {
            $quote = "If you tell the truth, you dont have to remember anything. - Mark Twain";
        } elseif ($number == 1) {
            $quote = "Be yourself; everyone else is already taken. - Oscar Wilde";
        } else {
            $quote = "In three words I can sum up everything Ive learned about life: it goes on. - Robert Frost";
        }
        $data = [
            'Quote' => $quote,
            'the date is' => $todays_date,
            'the time is' => $todays_time,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
