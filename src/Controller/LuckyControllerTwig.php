<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/home", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/", name: "presentation")]
    public function presentation(): Response
    {
        return $this->render('presentation.html.twig');
    }

    #[Route("/api/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
}