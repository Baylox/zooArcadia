<?php

namespace App\Controller;

use App\Entity\Habitat;
use App\Form\HabitatType;
use App\Repository\HabitatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/dashboard/habitat')]
final class HabitatController extends AbstractController
{
    #[Route(name: 'dashboard_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $habitatRepository): Response
    {
        return $this->render('dashboard/habitat/index.html.twig', [
            'habitats' => $habitatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'dashboard_habitat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $habitat = new Habitat();
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function edit(Request $request, Habitat $habitat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('dashboard_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dashboard/habitat/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
        ]);
    }

    private SluggerInterface $slugger; // Instanciation de l'interface SluggerInterface

    public function __construct(SluggerInterface $slugger) // Constructeur du slugger
    {
        $this->slugger = $slugger;
    }

    #[Route('/admin/upload', name: 'upload', methods: ['POST'])] // Ma fonction pour l'upload
    public function ImageUpload(Request $request)
    {
        
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';


        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $this->slugger->slug($originalFilename) .'-'. uniqid() .'.  '. $uploadedFile->guessExtension(); //On utilise le slugger pour générer un nom unique (Anciennement Urlizer)
        dd($uploadedFile->move(
            $destination,
            $newFilename            
        ));
    }

    #[Route('/{id}', name: 'dashboard_habitat_delete', methods: ['POST'])]
    public function delete(Request $request, Habitat $habitat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($habitat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_habitat_index', [], Response::HTTP_SEE_OTHER);
    }
}
