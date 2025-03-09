<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Image;
use App\Service\UploaderImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/dash/image')]
#[IsGranted('ROLE_ADMIN')]
class ImageController extends AbstractController
{   
    // Liste des images d'un animal
    #[Route('/animal/{id}/list', name: 'dashboard_image_list', methods: ['GET'])]
    public function listImages(Animal $animal): Response
    {
        return $this->render('dashboard/image/list.html.twig', [
            'images' => $animal->getImages(),
            'animal' => $animal,
        ]);
    }
    // Liste de toutes les images
    #[Route('/list', name: 'dashboard_image_list_all', methods: ['GET'])]
    public function listAllImages(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->findAll();
        return $this->render('dashboard/image/list_all.html.twig', [
            'images' => $images,
        ]);
    }
    // Confirmation de suppression d'une image
    #[Route('/{id}/confirm-delete', name: 'image_confirm_delete', methods: ['GET'])]
    public function confirmDelete(Image $image): Response
    {
        return $this->render('dashboard/image/delete.html.twig', [
            'image' => $image,
        ]);
    }
    // Suppression d'une image
    #[Route('/{id}/delete', name: 'dashboard_image_delete', methods: ['POST'])]
    public function deleteImage(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        // Supprime le fichier physique s'il existe
        $filePath = $this->getParameter('upload_directory') . '/' . $image->getFileName();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Supprime l'image de la base de données
        $entityManager->remove($image);
        $entityManager->flush();

        $this->addFlash('success', 'Image supprimée avec succès.');
        
        return $this->redirectToRoute('dashboard_image_list_all');
    }

    #[Route('/api/images/animaux', name: 'api_images_animaux', methods: ['GET'])]
    public function getImagesAnimaux(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->createQueryBuilder('i')
            ->where('i.animal IS NOT NULL')
            ->getQuery()
            ->getResult();
    
        return $this->json($this->formatImages($images));
    }
    
    #[Route('/api/images/habitats', name: 'api_images_habitats', methods: ['GET'])]
    public function getImagesHabitats(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->createQueryBuilder('i')
            ->where('i.habitat IS NOT NULL')
            ->getQuery()
            ->getResult();
    
        return $this->json($this->formatImages($images));
    }
    
    #[Route('/api/images/services', name: 'api_images_services', methods: ['GET'])]
    public function getImagesServices(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->createQueryBuilder('i')
            ->where('i.service IS NOT NULL')
            ->getQuery()
            ->getResult();
    
        return $this->json($this->formatImages($images));
    }
    
    private function formatImages($images): array
    {
        return array_map(fn($image) => [
            'id' => $image->getId(),
            'fileName' => $image->getFileName(),
            'path' => "/uploads/" . ($image->getAnimal() ? 'animaux_image' : ($image->getHabitat() ? 'habitats_image' : 'services_image')) . "/" . $image->getFileName(),
        ], $images);
    }    
}





