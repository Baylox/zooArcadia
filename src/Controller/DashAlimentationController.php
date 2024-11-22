<?php

namespace App\Controller;

use App\Entity\Alimentation;
use App\Form\AlimentationType;
use App\Repository\AlimentationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard/alimentation')]
#[IsGranted('ROLE_EMPLOYE')]
final class DashAlimentationController extends AbstractController
{   
    // Index des alimentations
    #[Route(name: 'dashboard_alimentation_index', methods: ['GET'])]
    public function index(AlimentationRepository $alimentationRepository): Response
    {
        return $this->render('dashboard/alimentation/index.html.twig', [
            'alimentations' => $alimentationRepository->findAll(),
        ]);
    }

    // Création d'une nouvelle alimentation
    #[Route('/new', name: 'dashboard_alimentation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $alimentation = new Alimentation();
        $form = $this->createForm(AlimentationType::class, $alimentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($alimentation);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_alimentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/alimentation/new.html.twig', [
            'alimentation' => $alimentation,
            'form' => $form,
        ]);
    }

    // Affichage d'une alimentation
    #[Route('/dashboard/alimentation/{id}', name: 'dashboard_alimentation_show', methods: ['GET'])]
    public function show(Alimentation $alimentation): Response
    {
        return $this->render('dashboard/alimentation/show.html.twig', [
            'alimentation' => $alimentation,
        ]);
    }

    // Modification d'une alimentation
    #[Route('/dashboard/alimentation/{id}/edit', name: 'dashboard_alimentation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alimentation $alimentation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlimentationType::class, $alimentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_alimentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/alimentation/edit.html.twig', [
            'alimentation' => $alimentation,
            'form' => $form,
        ]);
    }

    // Suppression d'une alimentation
    #[Route('/dashboard/alimentation/{id}', name: 'dashboard_alimentation_delete', methods: ['POST'])]
    public function delete(Request $request, Alimentation $alimentation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alimentation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($alimentation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_alimentation_index', [], Response::HTTP_SEE_OTHER);
    }
}
