<?php

namespace App\Controller;

use App\Entity\Alimentation;
use App\Entity\Rapport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AlimentationRapportType;
use App\Form\RapportType;


class AlimentationRapportController extends AbstractController
{
    #[Route('/gestion/rapport/alimentation', name: 'gestion_alimentation_rapport_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $alimentations = $entityManager->getRepository(Alimentation::class)->findAll();
        $rapports = $entityManager->getRepository(Rapport::class)->findAll();

        return $this->render('dashboard/gestion/index.html.twig', [
            'alimentations' => $alimentations,
            'rapports' => $rapports,
        ]);
    }

    #[Route('/gestion/rapport-alimentation/new', name: 'gestion_alimentation_rapport_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rapport = new Rapport();
        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire pour Alimentation
            $nomNourriture = $form->get('nomNourriture')->getData();
            $quantiteNourriture = $form->get('quantiteNourriture')->getData();
            $commentaireVeterinaire = $form->get('commentaireVeterinaire')->getData();

            if ($nomNourriture || $quantiteNourriture || $commentaireVeterinaire) {
                $alimentation = new Alimentation();
                $alimentation->setNomNourriture($nomNourriture);
                $alimentation->setQuantiteNourriture($quantiteNourriture);
                $alimentation->setCommentaireVeterinaire($commentaireVeterinaire);

                // Associer Alimentation à Rapport
                $rapport->setAlimentation($alimentation);

                // Persister Alimentation
                $entityManager->persist($alimentation);
            }

            // Persister Rapport
            $entityManager->persist($rapport);
            $entityManager->flush();

            return $this->redirectToRoute('gestion_alimentation_rapport_index');
        }

        return $this->render('gestion/rapport_alimentation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/gestion/rapport/alimentation/{id}/edit', name: 'gestion_alimentation_rapport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rapport $rapport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('gestion_alimentation_rapport_index');
        }

        return $this->render('dashboard/gestion/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/gestion/rapport/alimentation/{id}', name: 'gestion_alimentation_rapport_delete', methods: ['POST'])]
    public function delete(Request $request, Rapport $rapport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapport->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rapport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gestion_alimentation_rapport_index');
    }
}



