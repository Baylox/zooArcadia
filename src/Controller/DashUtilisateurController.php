<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\EmailService;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\UtilisateurCreationType;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/dash/utilisateur')]
#[IsGranted('ROLE_ADMIN')]
final class DashUtilisateurController extends AbstractController
{
    private $emailService;
    private $passwordHasher;

    public function __construct(EmailService $emailService, UserPasswordHasherInterface $passwordHasher)
    {
        $this->emailService = $emailService;
        $this->passwordHasher = $passwordHasher;
    }
    #[Route(name: 'app_dash_utilisateur_index', methods: ['GET'])]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $utilisateurRepository->createQueryBuilder('u');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Page actuelle, indente d'1 à chaque next
            10 // Nombre d'éléments par page
        );

        return $this->render('dash_utilisateur/index.html.twig', [
            'utilisateurs' => $pagination,
            'page_title' => 'Tous les Utilisateurs',
        ]);
    }

    // Création d'un nouvel utilisateur
    #[Route('/new', name: 'app_dash_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurCreationType::class, $utilisateur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher un mot de passe par défaut ou généré si nécessaire
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, 'motdepassepardefaut');
            $utilisateur->setPassword($hashedPassword);
    
            $entityManager->persist($utilisateur);
            $entityManager->flush();
    
            $this->emailService->sendWelcomeEmail($utilisateur->getEmail());
    
            return $this->redirectToRoute('app_dash_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dash_utilisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    // Affichage d'un utilisateur en particulier
    #[Route('/{id}', name: 'app_dash_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('dash_utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    // Modification d'un utilisateur
    #[Route('/{id}/edit', name: 'app_dash_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ['is_edit' => true]); // Édition
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si un nouveau mot de passe a été soumis
            $newPassword = $form->get('password')->getData();

            if ($newPassword) {
                // Si un mot de passe est renseigné, on le hash et on l'applique
                $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $newPassword);
                $utilisateur->setPassword($hashedPassword);
            }

            // Sauvegarde des autres modifications
            $entityManager->flush();

            return $this->redirectToRoute('app_dash_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dash_utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    // Suppression d'un utilisateur
    #[Route('/{id}', name: 'app_dash_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dash_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
    
    // Liste des employés
    #[Route('/ajax/employes', name: 'ajax_utilisateurs_employes', methods: ['GET'])]
    public function ajaxEmployes(Request $request, UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator): JsonResponse
    {
        $queryBuilder = $utilisateurRepository->findEmployesQueryBuilder();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), 
            10
        );
    
        $data = [];
        foreach ($pagination as $utilisateur) {
            $data[] = [
                'email' => $utilisateur->getEmail(),
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'roles' => $utilisateur->getRoles(),
            ];
        }
    
        // Déboguer les données
        dump($data);
    
        return new JsonResponse($data);
    }

    #[Route('/ajax/veterinaires', name: 'ajax_utilisateurs_veterinaires', methods: ['GET'])]
    public function ajaxVeterinaires(Request $request, UtilisateurRepository $utilisateurRepository, PaginatorInterface $paginator): JsonResponse
    {
        $queryBuilder = $utilisateurRepository->findVeterinairesQueryBuilder();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10 
        );

        $data = [];
        foreach ($pagination as $utilisateur) {
            $data[] = [
                'email' => $utilisateur->getEmail(),
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'roles' => $utilisateur->getRoles(),
            ];
        }

        return new JsonResponse($data);
    }
}


