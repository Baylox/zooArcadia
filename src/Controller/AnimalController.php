<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_show')]
    public function show(Animal $animal, EntityManagerInterface $entityManager): Response
    {
        // Incrémentation du compteur
        $animal->incrementConsultations();
        $entityManager->flush();
    
        return $this->render('pages/animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }
    
}