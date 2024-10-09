<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    private UtilisateurRepository $utilisateurRepository;
    private RouterInterface $router;

    public function __construct(UtilisateurRepository $utilisateurRepository, RouterInterface $router)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return ($request->getPathInfo() === '/login' && $request->isMethod('POST'));
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        return new Passport(
            new UserBadge($email, function($userIdentifier) {
                $utilisateur = $this->utilisateurRepository->findOneBy(['email' => $userIdentifier]);

                if (!$utilisateur) {
                    throw new UserNotFoundException();
                }

                return $utilisateur;
            }),
            new CustomCredentials(function($credentials, Utilisateur $utilisateur) {
                return $credentials === 'password'; // Logique de test pour les credentials
            }, $password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse(
            $this->router->generate('app_home') // Redirige vers la page d'accueil après succès
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        dd('Authentification échouée');
    }
}

