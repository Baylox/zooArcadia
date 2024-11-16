<?php

namespace App\Controller;

use App\Entity\Espece;
use App\Form\EspeceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EspeceRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

#[Route('/dashboard/espece', name: 'espece_')]
class DashEspeceController extends AbstractController
{   
    // Affichage de la liste des espèces
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EspeceRepository $especeRepository): Response
    {
        return $this->render('dashboard/espece/index.html.twig', [
            'especes' => $especeRepository->findAll(),
        ]);
    }

    // Création d'une nouvelle espèce
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $espece = new Espece();
        $form = $this->createForm(EspeceType::class, $espece);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($espece);
            $entityManager->flush();

            $this->addFlash('success', 'Espèce créée avec succès !');

            return $this->redirectToRoute('espece_index');
        }

        return $this->render('dashboard/espece/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Modification d'une espèce existante
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Espece $espece, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EspeceType::class, $espece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Espèce modifiée avec succès.');

            return $this->redirectToRoute('espece_index'); 
        }

        return $this->render('dashboard/espece/edit.html.twig', [
            'form' => $form->createView(),
            'espece' => $espece,
        ]);
    }

    // Suppression d'une espèce
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Espece $espece, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $espece->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($espece);
                $entityManager->flush();
                $this->addFlash('success', 'Espèce supprimée avec succès.');
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'Impossible de supprimer cette espèce car elle est associée à des animaux.');
            }
        }
    
        return $this->redirectToRoute('espece_index');
    }
}




