<?php

namespace App\Controller;

use App\Data\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $products = $productRepository->findWithSearch($search);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("produits/{slug}", name="detail_product")
     * 
     * Cette fonction permet d afficher le detail d un produit
     */
    public function show(Product $product) 
    {
        return $this->render("product/show.html.twig",  [
            'product' => $product
        ]);
    }
}
