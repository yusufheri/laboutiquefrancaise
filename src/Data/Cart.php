<?php

namespace App\Data;

use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart {

    private $session;
    private $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager) {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add(Product $product)
    {
        $cart = $this->session->get('cart', []);
        $id = $product->getId();

        if (! (empty($cart[$id])) ){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
       if($this->session) {
           return $this->session->get('cart');
       }        
    }

    public function getFull()
    {
        $cartCompleted = [];

        $carts = $this->get();
        
        if($carts){
            foreach ($carts as $id => $quantity)
            {
                $product_object = $this->entityManager->getRepository(Product::class)->find($id);
               
                if (! $product_object) {
                    $this->delete($id);
                    continue;
                }
                $cartCompleted [] = [
                    "product" => $product_object,
                    "quantity" => $quantity
                ];
            }
        }

        return $cartCompleted;
    }

    public function remove()
    {
        $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);

        $this->session->set('cart', $cart);
        return $this->get();
    }

    public function update(Product $product, $qte)
    {
        $cart = $this->session->get('cart', []);
        $count_before = count($cart);

        if (isset($cart[$product->getId()])) {
            if ($qte  > 0){
                $cart[$product->getId()]++;
            } elseif ($qte < 0) {
                if ($cart[$product->getId()] > 1) {
                    $cart[$product->getId()]--;
                } else {
                    unset($cart[$product->getId()]);
                }
            }
        }

        $this->session->set('cart', $cart);
        $count_after = $this->get();
        if ($count_after != $count_before) { return true;} else {return false;}
    }
}