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
use App\Repository\AnimalRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\RapportService;

#[Route('/dashboard/rapport')]
#[IsGranted('ROLE_EMPLOYE')]
class DashRapportController extends AbstractController
{   
    // Route pour afficher les rapports
    #[Route('/', name: 'dashboard_rapport_index', methods: ['GET'])]
    public function index(RapportRepository $rapportRepository, AnimalRepository $animalRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère le prénom de l’animal sélectionné
        $animalPrenom = $request->query->get('animalPrenom', null);
        $order = $request->query->get('order', 'DESC'); // Tri par défaut : décroissant


        // Construire la requête pour les rapports
        $queryBuilder = $rapportRepository->createQueryBuilder('r')
            ->orderBy('r.dateRapport', $order); // Utilise order pour trier

        // Filtrer par animal avec la selection d'un animal
        if ($animalPrenom) {
            $queryBuilder
                ->join('r.animal', 'a')
                ->where('a.prenom = :prenom')
                ->setParameter('prenom', $animalPrenom);
        }

        $query = $queryBuilder->getQuery();

        // Récupère la valeur de la page, en s'assurant qu'elle est >= 1 pour éviter une erreur
        $page = max(1, $request->query->getInt('page', 1)); 

        // Paginater les rapports
        $rapports = $paginator->paginate(
            $query,
            $page, 
            10
        );

        // Récupère la liste des animaux pour la sélection
        $animaux = $animalRepository->findAll();

        return $this->render('dashboard/rapport/index.html.twig', [
            'rapports' => $rapports,
            'animaux' => $animaux,
            'selectedAnimalPrenom' => $animalPrenom,
            'currentOrder' => $order, // Passer l'ordre actuel au template
        ]);
    }
    
    // Route pour afficher les rapports filtrés par prénom d'animal
    #[Route('/animal/{animalPrenom}', name: 'dashboard_rapport_animal_prenom', methods: ['GET'])]
    public function byAnimalPrenom(string $animalPrenom, RapportRepository $rapportRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère les rapports pour l'animal sélectionné
        $query = $rapportRepository->findByAnimalPrenomQuery($animalPrenom);

        $rapports = $paginator->paginate(
            $query,
            max(1, $request->query->getInt('page', 0)),
            10
        );
        

        return $this->render('dashboard/rapport/index.html.twig', [
            'rapports' => $rapports,
            'selectedAnimalPrenom' => $animalPrenom,
        ]);
    }
    // Route pour afficher les rapports filtrés par date
    #[Route('/new', name: 'dashboard_rapport_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_VETERINAIRE')]
    public function new(Request $request, RapportService $rapportService): Response
    {
        $rapport = new Rapport();
        $rapport->setDateRapport(new \DateTime());
    
        $form = $this->createForm(RapportType::class, $rapport);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $rapportService->createRapportWithAlimentation($rapport, $form);
    
            return $this->redirectToRoute('dashboard_rapport_index');
        }
    
        return $this->render('dashboard/rapport/new.html.twig', [
            'form' => $form,
        ]);
    }
    
    // Route pour afficher un rapport en particulier
    #[Route('/{id}', name: 'dashboard_rapport_show', methods: ['GET'])]
    public function show(Rapport $rapport): Response
    {
        return $this->render('dashboard/rapport/show.html.twig', [
            'rapport' => $rapport,
        ]);
    }
    // Route pour modifier un rapport
    #[Route('/{id}/edit', name: 'dashboard_rapport_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_VETERINAIRE')]
    public function edit(Request $request, Rapport $rapport, RapportService $rapportService): Response
    {
        $form = $this->createForm(RapportType::class, $rapport);
    
        if ($rapport->getAlimentation()) {
            $form->get('nomNourriture')->setData($rapport->getAlimentation());
            $form->get('quantiteNourriture')->setData($rapport->getAlimentation()->getQuantiteNourriture());
            $form->get('commentaireVeterinaire')->setData($rapport->getAlimentation()->getCommentaireVeterinaire());
        }
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $rapportService->updateRapportWithAlimentation($rapport, $form);
    
            return $this->redirectToRoute('dashboard_rapport_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dashboard/rapport/edit.html.twig', [
            'rapport' => $rapport,
            'form' => $form->createView(),
        ]);
    }    
    
    // Route pour supprimer un rapport
    #[Route('/{id}', name: 'dashboard_rapport_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Restriction : seuls les administrateurs peuvent supprimer un rapport
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


