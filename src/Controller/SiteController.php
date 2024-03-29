<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/site')]
class SiteController extends AbstractController
{
    #[isGranted('ROLE_ADMIN')]
    #[Route('/', name: 'site_index', methods: ['GET', 'POST'])]
    public function index(SiteRepository $siteRepository,
                            Request $request,
                            EntityManagerInterface $entityManager): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/index.html.twig', [
            'sites' => $siteRepository->findAll(),
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/rechercheParNom', name: 'site_recherche_nom')]
    public function rechercheParSite(SiteRepository $siteRepository,
                                     Request $request,
                                     EntityManagerInterface $entityManager,
    ): Response
    {
        $terme = $request->get('terme');
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        $sites = $siteRepository->rechercheSiteParNom($terme);

        return $this->renderForm('site/index.html.twig', [
            'sites' => $sites,
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'site_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $site = new Site();
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/new.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'site_show', methods: ['GET'])]
    public function show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'site' => $site,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'site_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('site/edit.html.twig', [
            'site' => $site,
            'form' => $form,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'site_delete', methods: ['POST'])]
    public function delete(Request $request, Site $site, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$site->getId(), $request->request->get('_token'))) {
            $entityManager->remove($site);
            $entityManager->flush();
        }

        return $this->redirectToRoute('site_index', [], Response::HTTP_SEE_OTHER);
    }

}



