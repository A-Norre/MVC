<?php

namespace App\Controller;

use App\Entity\Losses;
use App\Repository\LossesRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LossesController extends AbstractController
{
    #[Route('/losses', name: 'app_losses')]
    public function index(): Response
    {
        return $this->render('losses/index.html.twig', [
            'controller_name' => 'LossesController',
        ]);
    }

    // #[Route('/losses/create', name: 'losses_create')]
    // public function createProduct(
    //     ManagerRegistry $doctrine
    // ): Response {
    //     $entityManager = $doctrine->getManager();
    
    //     $losses = new Losses();
    //     $losses->setLoss(0);
    
    //     // tell Doctrine you want to (eventually) save the Product
    //     // (no queries yet)
    //     $entityManager->persist($losses);
    
    //     // actually executes the queries (i.e. the INSERT query)
    //     $entityManager->flush();
    
    //     return new Response('Saved new losses with id '.$losses->getId());
    // }

    #[Route('/losses/show', name: 'losses_show_all')]
    public function showAllWinnings(
        LossesRepository $lossesRepository
    ): Response {
        $losses = $lossesRepository->findAll();
    
        $response = $this->json($losses);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
