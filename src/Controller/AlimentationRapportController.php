<?php

namespace App\Controller;

use App\Entity\Alimentation;
use App\Entity\Rapport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlimentationRapportController extends AbstractController
{
    #[Route('/gestion', name: 'gestion_alimentation_rapport_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $alimentations = $entityManager->getRepository(Alimentation::class)->findAll();
        $rapports = $entityManager->getRepository(Rapport::class)->findAll();

        return $this->render('dashboard/gestion/index.html.twig', [
            'alimentations' => $alimentations,
            'rapports' => $rapports,
        ]);
    }
}

