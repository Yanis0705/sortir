<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/profil/{id}', name: 'detail')]
    public function detail(
        Participant $participant
    ): Response
    {
        return $this->render('profil/detail.html.twig',
            compact('participant')
        );
    }


    #[Route('/update/{id}', name: 'update')]
    public function update(
        Request                $request,
                               $id,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        Participant            $participant,
        ParticipantRepository  $participantRepository)
    {
        $participant = $this->getUser();

        $form = $this->createForm(ProfilType::class, $participant);
        $form->handleRequest($request);

        /*dd(filter_input(INPUT_POST, 'verifpass', FILTER_SANITIZE_STRING));*/

            if ($form->isSubmitted()
                && $form->isValid()
            ) {
                /* //est ce que le pseudo est unique
                 //utilisation d'une méthode que nous allons ajouter au repository
                 if($participantRepository->findOneByPseudo($participant->getPseudo()) != null)
                 {// s'il ne l'est pas, on renvoie vers la page de création d'utilisateur avec une notification
                     return $this->render('profil/index.html.twig', [
                         'participant' => $participant,
                         'form'        => $form->createView(),
                         'message'     => "Ce pseudo est déjà utilisé, merci d'en changer"
                     ]);
                 }*/

                $participant->setPassword(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $form->get('password')->getData()
                    )
                );

                $entityManager->persist($participant);
                $entityManager->flush();
                $this->addFlash('bravo', 'Votre profil a été modifié');
                /*return $this->redirectToRoute('main_accueil', ['id' => $participant->getId()]);*/
            }

            return $this->renderForm('profil/index.html.twig',
                compact("form") // ["monFormulaireIdee" => $monFormulaireIdee]
            );
        }

}