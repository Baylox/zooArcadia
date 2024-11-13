<?php

namespace App\Controller;

use App\Entity\Rapport;
use App\Entity\Alimentation;
use App\Form\RapportType;
use App\Repository\RapportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dash/rapport')]
final class DashRapportController extends AbstractController
{
    #[Route(name: 'dashboard_rapport_index', methods: ['GET'])]
    public function index(RapportRepository $rapportRepository): Response
    {
        return $this->render('dashboard/rapport/index.html.twig', [
            'rapports' => $rapportRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'dashboard_rapport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rapport = new Rapport();
        $rapport->setDateRapport(new \DateTime()); // Définir la date d'aujourd'hui par défaut
        
        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'objet Alimentation sélectionné
            $alimentationSelectionnee = $form->get('nomNourriture')->getData();
            
            if ($alimentationSelectionnee) {
                $nomNourriture = $alimentationSelectionnee->getNomNourriture(); // Accès au nom de l'alimentation
        
                // Assurez-vous que la quantité et le commentaire sont récupérés correctement
                $quantiteNourriture = $form->get('quantiteNourriture')->getData();
                $commentaireVeterinaire = $form->get('commentaireVeterinaire')->getData();
        
                // Créez une instance d'Alimentation si nécessaire
                $alimentation = new Alimentation();
                $alimentation->setNomNourriture($nomNourriture);
                $alimentation->setQuantiteNourriture($quantiteNourriture);
                $alimentation->setCommentaireVeterinaire($commentaireVeterinaire);
        
                // Liez l'alimentation au rapport et persistez-la
                $rapport->setAlimentation($alimentation);
                $entityManager->persist($alimentation);
            }
        
            // Persister le rapport
            $entityManager->persist($rapport);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_rapport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/rapport/new.html.twig', [
            'rapport' => $rapport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'dashboard_rapport_show', methods: ['GET'])]
    public function show(Rapport $rapport): Response
    {
        return $this->render('dashboard/rapport/show.html.twig', [
            'rapport' => $rapport,
        ]);
    }

    #[Route('/{id}/edit', name: 'dashboard_rapport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rapport $rapport, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RapportType::class, $rapport);
        
        if ($rapport->getAlimentation()) {
            $form->get('nomNourriture')->setData($rapport->getAlimentation()); // Utilise l'objet Alimentation entier
            $form->get('quantiteNourriture')->setData($rapport->getAlimentation()->getQuantiteNourriture());
            $form->get('commentaireVeterinaire')->setData($rapport->getAlimentation()->getCommentaireVeterinaire());
        }
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données pour l'entité Alimentation
            $alimentationSelectionnee = $form->get('nomNourriture')->getData(); // Objet Alimentation sélectionné
            $quantiteNourriture = $form->get('quantiteNourriture')->getData();
            $commentaireVeterinaire = $form->get('commentaireVeterinaire')->getData();
        
            // Utiliser l'alimentation sélectionnée ou en créer une nouvelle
            $alimentation = $rapport->getAlimentation() ?: new Alimentation();
        
            // Mettre à jour les champs d'Alimentation
            $alimentation->setNomNourriture($alimentationSelectionnee->getNomNourriture());
            $alimentation->setQuantiteNourriture($quantiteNourriture);
            $alimentation->setCommentaireVeterinaire($commentaireVeterinaire);
        
            // Associer l'alimentation au rapport et la persister si nouvelle
            if (!$rapport->getAlimentation()) {
                $rapport->setAlimentation($alimentation);
                $entityManager->persist($alimentation);
            }
        
            $entityManager->persist($rapport);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_rapport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/rapport/edit.html.twig', [
            'rapport' => $rapport,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'dashboard_rapport_delete', methods: ['POST'])]
    public function delete(Request $request, Rapport $rapport, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapport->getId(), $request->request->get('_token'))) {
            if ($rapport->getAlimentation()) {
                $entityManager->remove($rapport->getAlimentation());
            }
            $entityManager->remove($rapport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_rapport_index', [], Response::HTTP_SEE_OTHER);
    }
}

