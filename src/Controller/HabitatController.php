<?php

namespace App\Controller;

use App\Entity\Habitat;
use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HabitatController extends AbstractController
{
    #[Route('/habitat', name: 'app_habitat')]
    public function index(HabitatRepository $habitatRepository): Response
    {
        $habitats = $habitatRepository->findAll();
    
        return $this->render('pages/habitat/index.html.twig', [
            'habitats' => $habitats,
        ]);
    }
    

    #[Route('/habitat/{id}/animaux', name: 'app_habitat_show')]
    public function show(Habitat $habitat): Response
    {
        return $this->render('pages/habitat/show.html.twig', [
            'habitat' => $habitat,
            'animals' => $habitat->getAnimaux(),
        ]);
    }    
}


