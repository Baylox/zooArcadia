<?php

namespace App\Controller;

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

    #[Route('/habitat/{id}', name: 'app_habitat_show')]
    public function show(int $id, HabitatRepository $habitatRepository): Response
    {
        $habitat = $habitatRepository->find($id);

        if (!$habitat) {
            throw $this->createNotFoundException('Habitat non trouvé.');
        }

        $animals = $habitat->getAnimals();

        return $this->render('pages/habitat/show.html.twig', [
            'habitat' => $habitat,
            'animals' => $animals,
        ]);
    }
}

