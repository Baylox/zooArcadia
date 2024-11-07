<?php

namespace App\Controller;

use App\Document\Horaire;
use App\Form\HoraireType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HoraireController extends AbstractController
{
    #[Route('/horaires', name: 'horaire_index')]
    public function index(DocumentManager $dm): Response
    {
        $horaires = $dm->getRepository(Horaire::class)->findAll();

        return $this->render('horaire/index.html.twig', [
            'horaires' => $horaires,
        ]);
    }

    #[Route('/horaires/{id}/edit', name: 'horaire_edit')]
    public function edit(string $id, DocumentManager $dm, Request $request): Response
    {
        $horaire = $dm->getRepository(Horaire::class)->find($id);

        if (!$horaire) {
            throw $this->createNotFoundException('Horaire non trouvé');
        }

        // Stocke le jour original avant toute modification
        $originalJour = $horaire->getJour();

        $form = $this->createForm(HoraireType::class, $horaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie que le jour n'a pas été modifié dans la requête
            if ($horaire->getJour() !== $originalJour) {
                $this->addFlash('error', 'La modification du jour n\'est pas autorisée.');
                return $this->redirectToRoute('horaire_index');
            }

            $dm->flush();

            return $this->redirectToRoute('horaire_index');
        }

        return $this->render('horaire/edit.html.twig', [
            'form' => $form->createView(),
            'horaire' => $horaire,
        ]);
    }
        /*#[Route('/horaires/{id}/delete', name: 'horaire_delete', methods: ['POST'])]
    public function delete(string $id): Response
    {
        $this->addFlash('error', 'La suppression des horaires est désactivée.');
        return $this->redirectToRoute('app_home');
    }*/
}








