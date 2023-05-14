<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/library', name: 'library')]
    public function library(
        // ManagerRegistry $doctrine
    ): Response {

        return $this->render('product/library.html.twig');
    }

    #[Route('/product/create', name: 'product_create')]
    public function createProduct(
        // ManagerRegistry $doctrine
    ): Response {
        // $entityManager = $doctrine->getManager();
    
        // $product = new Product();
        // //$product->setName('Keyboard_num_' . rand(1, 9));
        // //$product->setValue(rand(100, 999));
        // $product->setTitle("Clean Code");
        // $product->setIsbn(9780132350884);
        // $product->setAuthor("Robert Martin");
        // $product->setImage("picture1");
    
        // // tell Doctrine you want to (eventually) save the Product
        // // (no queries yet)
        // $entityManager->persist($product);
    
        // // actually executes the queries (i.e. the INSERT query)
        // $entityManager->flush();
    
        // return new Response('Saved new product with id '.$product->getId());
        return $this->render('product/create_product.html.twig');
    }

    #[Route('/product/create/write', name: 'product_create_write')]
    public function createProductWrite(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $title = $_POST["title"];
        $isbn = $_POST["isbn"];
        $author = $_POST["author"];
        $image = $_POST["image"];

        $product = new Product();
        //$product->setName('Keyboard_num_' . rand(1, 9));
        //$product->setValue(rand(100, 999));
        $product->setTitle($title);
        $product->setIsbn($isbn);
        $product->setAuthor($author);
        $product->setImage($image);
    
        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($product);
    
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
    
        return $this->redirectToRoute('product_create');
    }

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

    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository->findAll();
    
        // var_dump($products);
        $data = [
            // "products" => $products[0]->getTitle(),
            "products" => $products,
        ];

        return $this->render('product/product_show_all.html.twig', $data);
    }

    #[Route('/product/show/{id}', name: 'product_by_id')]
    public function showProductById(
        ProductRepository $productRepository,
        int $id
    ): Response {
        $products = $productRepository->find($id);

        $data = [
            // "products" => $products[0]->getTitle(),
            "products" => $products,
            "picture" => $products->getImage(),
        ];

        return $this->render('product/product_show_spec.html.twig', $data);
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

    #[Route('/product/delete/{id}', name: 'product_delete_by_id')]
    public function deleteProductById(
        ProductRepository $productRepository,
        int $id
    ): Response {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $productRepository->remove($product, true);

        return $this->redirectToRoute('product_show_all');
    }

    #[Route('/product/update', name: 'product_update')]
    public function updateProduct(
        ProductRepository $productRepository,
        // int $id,
        // string $value
    ): Response {
        $id = $_POST["id"];
        $title = $_POST["title"];
        $isbn = $_POST["isbn"];
        $author = $_POST["author"];
        // $image = $_POST["image"];
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setTitle($title);
        $product->setIsbn($isbn);
        $product->setAuthor($author);
        // $product->setImage($image);
        $productRepository->save($product, true);

        return $this->redirectToRoute('product_show_all');
    }
}
