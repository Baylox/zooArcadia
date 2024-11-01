<?php

namespace App\Controller;

use App\Document\Avis;
use App\Form\AvisType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(Request $request, DocumentManager $dm): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setIsValide(false); 
            $dm->persist($avis);
            $dm->flush();
            dd($avis);

            // Ajoute un message flash pour informer l'utilisateur
            $this->addFlash('avis_submitted', 'Votre avis a été soumis et est en attente de validation par un administrateur.');

            // Redirige vers la même page pour éviter la resoumission du formulaire
            return $this->redirectToRoute('app_avis');
        }

        return $this->render('pages/avis/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

