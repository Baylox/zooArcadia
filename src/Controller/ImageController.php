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
    #[Route('/animal/{id}/list', name: 'dashboard_image_list', methods: ['GET'])]
    public function listImages(Animal $animal): Response
    {
        return $this->render('dashboard/image/list.html.twig', [
            'images' => $animal->getImages(),
            'animal' => $animal,
        ]);
    }

    #[Route('/list', name: 'dashboard_image_list_all', methods: ['GET'])]
    public function listAllImages(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->findAll();
        return $this->render('dashboard/image/list_all.html.twig', [
            'images' => $images,
        ]);
    }

    #[Route('/{id}/confirm-delete', name: 'image_confirm_delete', methods: ['GET'])]
    public function confirmDelete(Image $image): Response
    {
        return $this->render('dashboard/image/delete.html.twig', [
            'image' => $image,
        ]);
    }

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

    #[Route('/animal/{id}/add', name: 'dashboard_image_add', methods: ['GET', 'POST'])]
    public function addImage(Request $request, Animal $animal, UploaderImage $uploaderImage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('image', FileType::class, [
                'label' => 'Ajouter une image (PNG, JPEG)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/png', 'image/jpeg'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide',
                    ]),
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            $newFilename = $uploaderImage->uploadAnimalImage($uploadedFile);

            // Crée et associe une nouvelle image à l'animal
            $image = new Image();
            $image->setFileName($newFilename);
            $image->setAnimal($animal);

            $entityManager->persist($image);
            $entityManager->flush();

            $this->addFlash('success', 'Image ajoutée avec succès.');
            return $this->redirectToRoute('dashboard_image_list', ['id' => $animal->getId()]);
        }

        return $this->render('dashboard/image/add.html.twig', [
            'form' => $form->createView(),
            'animal' => $animal,
        ]);
    }
}




