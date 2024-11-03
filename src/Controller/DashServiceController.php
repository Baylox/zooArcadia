<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Service\UploaderImage;
use App\Entity\Image;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dash/service')]
final class DashServiceController extends AbstractController
{
    #[Route(name: 'dashboard_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('dashboard/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'dashboard_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            
            if ($uploadedFile) {
                $newFilename = $uploaderImage->uploadServiceImage($uploadedFile);
                $image = new Image();
                $image->setFileName($newFilename);
                $image->setService($service);
                $entityManager->persist($image);
            }
            
            $entityManager->persist($service);
            $entityManager->flush();
    
            return $this->redirectToRoute('dashboard_service_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dashboard/service/new.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'dashboard_service_show', methods: ['GET'])]
    public function show(Service $service): Response
    {
        return $this->render('dashboard/service/show.html.twig', [
            'service' => $service,
        ]);
    }

    #[Route('/{id}/edit', name: 'dashboard_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, EntityManagerInterface $entityManager, UploaderImage $uploaderImage): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            
            if ($uploadedFile) {
                $newFilename = $uploaderImage->uploadServiceImage($uploadedFile);
                
                // Crée une nouvelle instance de l'entité Image
                $image = new Image();
                $image->setFileName($newFilename);
                $image->setService($service);  // Associe l'image au service
                $entityManager->persist($image); // Persiste l'image en base de données
            }
            $entityManager->flush();

            $this->addFlash('success', 'Image téléchargée avec succès.');

            return $this->redirectToRoute('dashboard_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'dashboard_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
