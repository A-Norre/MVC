<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController2 extends AbstractController
{
    #[Route('/product/library/books', name: 'product_show_all_api')]
    public function showAllProductApi(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository->findAll();

        // return $this->json($products);
        $response = $this->json($products);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/product/library/book/{isbn}', name: 'product_by_id_api')]
    public function showProductByIdApi(
        ProductRepository $productRepository,
        int $isbn
    ): Response {
        // $product = $productRepository->find($id);
        $id = 0;
        $products = $productRepository->findAll();
        $sumProducts = count($products);
        for ($x = 0; $x < $sumProducts; $x++) {
            if ($products[$x]->getIsbn() == $isbn) {
                $id = $products[$x]->getId();
            }
        }

        $product = $productRepository->find($id);

        $response = $this->json($product);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;

        //return $this->json($product);
    }
}
