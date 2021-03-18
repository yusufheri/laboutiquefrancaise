<?php

namespace App\Controller;

use App\Data\Cart;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart, ProductRepository $repo): Response
    {
        

            
        return $this->render('cart/index.html.twig', [
            'carts' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name = "add_to_cart")
     * Ajout d un produit dans le panier
     *
     * @param Cart $cart
     * @param Product $product
     * @return string
     */
    public function add(Cart $cart, Product $product) 
    {
        $cart->add($product);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name = "remove_my_cart")
     * Supprimer tous les produits dans le panier
     *
     * @param Cart $cart
     */
    public function remove(Cart $cart) 
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/{id}/delete", name = "delete_to_cart")
     * Supprimer tous les produits dans le panier
     *
     * @param Product $product
     * 
     */
    public function delete(Cart $cart, $id) 
    {
        $mon_panier_before = $cart->get();
        $mon_panier_after = $cart->delete($id);

        return new Response(count($mon_panier_before).":".count($mon_panier_after));
    }

    /**
     * Fait la mise à jour du panier
     * 
     * @Route("/cart/{id}/update", name="update_to_cart")
     *
     * @param Request $request
     * @param Cart $cart
     * @param Product $product
     * @return Response
     */
    public function update(Request $request, Cart $cart, Product $product)
    {
        $response = $cart->update($product, $request->get("qte"));
        

        return new Response($response);
    }
}
