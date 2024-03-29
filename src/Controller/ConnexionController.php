<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Cookie;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, ParticipantRepository $participantRepository): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('main_accueil');
        }


        //récupérer bouton name
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();


        return $this->render('security/login.html.twig', ['error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
