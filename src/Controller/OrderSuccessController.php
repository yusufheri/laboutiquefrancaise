<?php

namespace App\Controller;

use App\Data\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager= $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index(Cart $cart, string $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $this->getUser() != $order->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        if (!$order->getIsPaid()){
            //Vider le panier de l utilisateur
            $cart->remove();
            
            $order->setIsPaid(true);
            $this->entityManager->flush();
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
