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
use Knp\Component\Pager\PaginatorInterface;

final class DashRapportController extends AbstractController
{
    #[Route('dash/rapport', name: 'dashboard_rapport_index', methods: ['GET'])]
    public function index(RapportRepository $rapportRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère tous les rapports avec une requête paginée
        $query = $rapportRepository->createQueryBuilder('r')
        ->orderBy('r.dateRapport', 'DESC')
        ->getQuery();

        // Utilisation du paginator pour gérer la pagination
        $rapports = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('dashboard/rapport/index.html.twig', [
            'rapports' => $rapports,
        ]);
    }

    #[Route('/new', name: 'dashboard_rapport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rapport = new Rapport();
        $rapport->setDateRapport(new \DateTime());

        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alimentationSelectionnee = $form->get('nomNourriture')->getData();

            if ($alimentationSelectionnee) {
                $nomNourriture = $alimentationSelectionnee->getNomNourriture();
                $quantiteNourriture = $form->get('quantiteNourriture')->getData();
                $commentaireVeterinaire = $form->get('commentaireVeterinaire')->getData();

                $alimentation = new Alimentation();
                $alimentation->setNomNourriture($nomNourriture);
                $alimentation->setQuantiteNourriture($quantiteNourriture);
                $alimentation->setCommentaireVeterinaire($commentaireVeterinaire);

                $rapport->setAlimentation($alimentation);
                $entityManager->persist($alimentation);
            }

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
            $form->get('nomNourriture')->setData($rapport->getAlimentation());
            $form->get('quantiteNourriture')->setData($rapport->getAlimentation()->getQuantiteNourriture());
            $form->get('commentaireVeterinaire')->setData($rapport->getAlimentation()->getCommentaireVeterinaire());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alimentationSelectionnee = $form->get('nomNourriture')->getData();
            $quantiteNourriture = $form->get('quantiteNourriture')->getData();
            $commentaireVeterinaire = $form->get('commentaireVeterinaire')->getData();

            $alimentation = $rapport->getAlimentation() ?: new Alimentation();
            $alimentation->setNomNourriture($alimentationSelectionnee->getNomNourriture());
            $alimentation->setQuantiteNourriture($quantiteNourriture);
            $alimentation->setCommentaireVeterinaire($commentaireVeterinaire);

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


