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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\EmailService;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/dash/utilisateur')]
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
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('dash_utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dash_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe avant de l'enregistrer
            $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($hashedPassword);

            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // Envoyer l'email de bienvenue
            $this->emailService->sendWelcomeEmail($utilisateur->getEmail());

            return $this->redirectToRoute('app_dash_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dash_utilisateur/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_dash_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('dash_utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

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

    #[Route('/{id}', name: 'app_dash_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dash_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
