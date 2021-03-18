<?php

namespace App\Controller;

use App\Data\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        //$mail = new Mail();
        //$mail->send("yusufheri64@gmail.com", "YUSUF HERI", "Test", "Bonjour Yusuf Heri, <br/> J'espère que tu vas bien !!");
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
