<?php

namespace App\Controller;

use App\Entity\Winnings;
use App\Repository\WinningsRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WinningsController extends AbstractController
{
    #[Route('/winnings', name: 'app_winnings')]
    public function index(): Response
    {
        return $this->render('winnings/index.html.twig', [
            'controller_name' => 'WinningsController',
        ]);
    }

    // #[Route('/winnings/create', name: 'winnings_create')]
    // public function createProduct(
    //     ManagerRegistry $doctrine
    // ): Response {
    //     $entityManager = $doctrine->getManager();
    
    //     $winnings = new Winnings();
    //     $winnings->setProfit(0);
    
    //     // tell Doctrine you want to (eventually) save the Product
    //     // (no queries yet)
    //     $entityManager->persist($winnings);
    
    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();
    
    //     return new Response('Saved new winnings with id '.$winnings->getId());
    // }


    #[Route('/winnings/show', name: 'winnings_show_all')]
    public function showAllWinnings(
        WinningsRepository $winningsRepository
    ): Response {
        $winnings = $winningsRepository->findAll();
    
        $response = $this->json($winnings);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
