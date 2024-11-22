<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Entity\Image;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\UploaderImage;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dash/animal')]
#[IsGranted('ROLE_EMPLOYE')]
final class DashAnimalController extends AbstractController
{   
    // Index des animaux
    #[Route(name: 'dashboard_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('dashboard/animal/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }
    
    // Création d'un nouvel animal
    #[Route('/new', name: 'dashboard_animal_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            
            if ($uploadedFile) {
                $newFilename = $uploaderImage->uploadAnimalImage($uploadedFile);
                $image = new Image();
                $image->setFileName($newFilename);
                $image->setAnimal($animal);
                $entityManager->persist($image);
            }
            
            $entityManager->persist($animal);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard_animal_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dashboard/animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }
    
    // Affichage d'un animal
    #[Route('/{id}', name: 'dashboard_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('dashboard/animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    // Modification d'un animal
    #[Route('/{id}/edit', name: 'dashboard_animal_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Animal $animal, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de la nouvelle image
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('image')->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderImage->uploadAnimalImage($uploadedFile);

                // Créer une nouvelle entité Image
                $image = new Image();
                $image->setFileName($newFilename);
                $image->setAnimal($animal);

                // Associer l'image à l'animal
                $animal->addImage($image);
                $entityManager->persist($image);
            }

            $entityManager->flush();

            // Ajouter un message flash après le succès de l'opération
            $this->addFlash('success', 'Animal mis à jour avec succès.');
            return $this->redirectToRoute('dashboard_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }
    

    // Suppression d'un animal 
    #[Route('/{id}', name: 'dashboard_animal_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_animal_index', [], Response::HTTP_SEE_OTHER);
    }
}
