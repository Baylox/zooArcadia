<?php

namespace App\Controller;

use App\Entity\Habitat;
use App\Form\HabitatType;
use App\Entity\Image;
use App\Repository\HabitatRepository;
use App\Service\UploaderImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_VETERINAIRE')]
#[Route('/dashboard/habitat')]
final class DashHabitatController extends AbstractController
{
    #[Route(name: 'dashboard_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $habitatRepository): Response
    {
        return $this->render('dashboard/habitat/index.html.twig', [
            'habitats' => $habitatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'dashboard_habitat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $habitat = new Habitat();
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload de l'image
            $uploadedFile = $form->get('image')->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderImage->uploadHabitatImage($uploadedFile);
                $image = new Image();
                $image->setFileName($newFilename);
                $image->setHabitat($habitat);  
    
                $entityManager->persist($image);
            }
            $entityManager->persist($habitat);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard_habitat_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dashboard/habitat/new.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'dashboard_habitat_show', methods: ['GET'])]
    public function show(Habitat $habitat): Response
    {
        return $this->render('dashboard/habitat/show.html.twig', [
            'habitat' => $habitat,
        ]);
    }

    #[Route('/{id}/edit', name: 'dashboard_habitat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitat $habitat, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            
            if ($uploadedFile) {
                $newFilename = $uploaderImage->uploadHabitatImage($uploadedFile);
                
                // Crée une nouvelle instance de l'entité Image
                $image = new Image();
                $image->setFileName($newFilename);
                $image->setHabitat($habitat);  // Associe l'image à l'habitat
                $entityManager->persist($image); // Persiste l'image en base de données
            }
            $entityManager->flush();

            $this->addFlash('success', 'Image téléchargée avec succès.');

            return $this->redirectToRoute('dashboard_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/habitat/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'dashboard_habitat_delete', methods: ['POST'])]
    public function delete(Request $request, Habitat $habitat, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'habitat contient des animaux
        if (!$habitat->getAnimaux()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer cet habitat car des animaux y sont encore associés.');
            return $this->redirectToRoute('dashboard_habitat_index');
        }

        if ($this->isCsrfTokenValid('delete' . $habitat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($habitat);
            $entityManager->flush();
            $this->addFlash('success', 'Habitat supprimé avec succès.');
        }

        return $this->redirectToRoute('dashboard_habitat_index');
    }
}
