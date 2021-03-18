<?php

namespace App\Controller;

use App\Data\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-checkout-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $manager, Cart $cart, $reference)
    {
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $products_for_stripe = [];

        $order = $manager->getRepository(Order::class)->findOneByReference($reference);
        

        if(! $order) {
            new JsonResponse(['error' => 'order']);
        }

        Stripe::setApiKey('sk_test_C11W6nnFpsUfv1UfbQvhqRiD');

        foreach($order->getOrderDetails()->getValues() as $product) {
            $product_object = $manager->getRepository(Product::class)->findOneByName($product->getProduct());

            $products_for_stripe [] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];

        }

        $products_for_stripe [] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => ["https://www.tarif-colis.com/img/min/images/colis_boys/colisboy-33_218x225.png"],
                ],
            ],
            'quantity' => 1,
        ];

        //dd($products_for_stripe);

        $checkout_session =  \Stripe\Checkout\Session::create([
        'customer_email' => $this->getUser()->getEmail(),
        'payment_method_types' => ['card'],
        'line_items' => [
            $products_for_stripe
        ],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
        'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $manager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
           
    }
}
