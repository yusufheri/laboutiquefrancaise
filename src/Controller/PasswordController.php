<?php

namespace App\Controller;

use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mon-compte/update-password", name="updatepassword")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $user);
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $old_password = $form->get('old_password')->getData();
            
            if ($encoder->isPasswordValid($user, $old_password)) {

                $new_pasword = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pasword);

                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                $this->addFlash("success", "Votre mot de passe a été modifié avec succès");
                return $this->redirectToRoute("account");
            } else {
                $this->addFlash("danger", "L'actuel mot de passe saisi n est pas correct !!");
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
