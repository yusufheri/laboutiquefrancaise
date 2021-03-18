<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager; 
    }
    /**
     * @Route("/mon-compte/mes-adresses", name="account_address")
     */
    public function index(Request $request): Response
    {
        $array = $request->get('adress');
        $user = $this->getUser();

        if(isset($array) && isset($user)) {
            $obj = json_decode(json_encode($array, false));
            $address = new Address();
            $address
                    ->setName($obj->name)
                    ->setFirstname($obj->firstname)
                    ->setLastname($obj->lastname)
                    ->setAddress($obj->address)
                    ->setPostal($obj->postal)
                    ->setCity($obj->city)
                    ->setCountry($obj->country)
                    ->setPhone($obj->phone)
                    ->setUser($this->getUser());

            
                $this->manager->persist($address);
                $this->manager->flush();

                if (! is_null($request->query->get('modal'))){
                    return $this->redirectToRoute("order");
                } else {
                    return $this->redirectToRoute("account_address");
                }
                
        }
        

        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }

    /**
     * Permet d 'ajouter une nouvelle adresse de l utilisteur
     * @Route("/mon-compte/nouvelle-adresse", name="account_address_add")
     *
     * @return Response
     */
    public function add(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AdressType::class, $address);

        
        if($request->isXmlHttpRequest()) 
        {   

            return $this->render("partials/form_address.html.twig", [
                "form" => $form->createView()
            ]);
        } else {
            return new Response("denied access !!!", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Permet de supprimer une adresse
     * @Route("/mon-compte/address/{id}/delete")
     * @param Request $request
     * @param Address $address
     * @return Response
     */
    public function delete(Request $request, Address $address):Response
    {
        if ($request->isXmlHttpRequest()){
            if ($address && ($this->getUser() == $address->getUser())) {
                $this->manager->remove($address);
                $this->manager->flush();

                return new Response("success", Response::HTTP_OK);
            } else {
                return new Response("FORBIDDEN", Response::HTTP_FORBIDDEN);
            }
        } else {return new Response("FORBIDDEN", Response::HTTP_FORBIDDEN);}
        
    }


}
