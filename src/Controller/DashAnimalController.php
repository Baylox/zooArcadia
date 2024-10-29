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

#[Route('/dash/animal')]
final class DashAnimalController extends AbstractController
{
    #[Route(name: 'dashboard_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('dashboard/animal/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'dashboard_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'dashboard_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('dashboard/animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/{id}/edit', name: 'dashboard_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Supprimer l'image existante si la case est cochée
            if ($form->get('removeImage')->getData() && $animal->getImages()->count() > 0) {
                $image = $animal->getImages()->first();
                $filePath = $this->getParameter('upload_directory') . '/' . $image->getFileName();
    
                // Ajouter un log ou un echo pour vérifier le chemin
                echo "Chemin du fichier : " . $filePath;  // Pour voir le chemin du fichier dans la console
    
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        // Fichier supprimé avec succès
                        $animal->removeImage($image);
                        $entityManager->remove($image);
                        $this->addFlash('success', 'Image supprimée avec succès.');
                    } else {
                        // Erreur lors de la suppression du fichier
                        $this->addFlash('error', 'Erreur lors de la suppression du fichier du système de fichiers.');
                    }
                } else {
                    // Le fichier n'existe pas
                    $this->addFlash('warning', 'Le fichier à supprimer n\'a pas été trouvé.');
                }
            }
    
            // Gestion de l'upload de la nouvelle image
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('image')->getData();
    
            if ($uploadedFile) {
                $newFilename = $uploaderImage->upload($uploadedFile);
    
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
    

    #[Route('/{id}', name: 'dashboard_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_animal_index', [], Response::HTTP_SEE_OTHER);
    }
}
