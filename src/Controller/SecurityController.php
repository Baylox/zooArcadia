<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Sécurité : Authentification
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    // Sécurité : Déconnexion
    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
    throw new \Exception('Cette méthode peut être vide car elle sera interceptée par la clé de déconnexion du firewall mais on la remplie quand même pour éviter les erreurs');
    }
}

