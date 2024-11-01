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
            // Nettoyage des données pour éviter les balises HTML malveillantes
            $avis->setPseudo(strip_tags($avis->getPseudo()));
            $avis->setCommentaire(strip_tags($avis->getCommentaire()));
            
            $avis->setIsValide(false); 
            $dm->persist($avis);
            $dm->flush();
            $this->addFlash('avis_submitted', 'Votre avis a été soumis et est en attente de validation par un administrateur.');

            
            return $this->redirectToRoute('app_avis');
        }

        return $this->render('pages/avis/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


