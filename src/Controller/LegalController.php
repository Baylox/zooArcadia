<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/mentions-legales', name: 'mentions_legales', methods: ['GET'])]
    public function mentionsLegales(): Response
    {
        return $this->render('legal/mentions_legales.html.twig', [
            'page_title' => 'Mentions légales',
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'politique_confidentialite', methods: ['GET'])]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('legal/confidentialite.html.twig', [
            'page_title' => 'Politique de confidentialité',
        ]);
    }
}
