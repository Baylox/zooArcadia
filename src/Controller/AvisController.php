<?php

namespace App\Controller;

use App\Document\Avis;
use App\Form\AvisType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis', methods: ['GET', 'POST'])]
    
    public function index(Request $request, DocumentManager $dm, ValidatorInterface $validator): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Nettoyage des données pour éviter les balises HTML malveillantes
            $avis->setPseudo(htmlspecialchars($avis->getPseudo(), ENT_QUOTES, 'UTF-8'));
            $avis->setCommentaire(htmlspecialchars($avis->getCommentaire(), ENT_QUOTES, 'UTF-8'));
            
            // Revalider l'objet après nettoyage
            $errors = $validator->validate($avis);

            // Si des erreurs sont trouvées, les afficher
            if (count($errors) > 0) {
                return $this->render('pages/avis/index.html.twig', [
                    'form' => $form->createView(),
                    'errors' => $errors, // Transmission des erreurs
                ]);
            }            

             // Persistance des données
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
    #[Route('/avis/valides', name: 'app_valide_avis', methods: ['GET'])]
    public function validatedAvis(DocumentManager $dm): Response
    {
        // Récupère uniquement les avis validés, sans tri
        $avis_valides = $dm->getRepository(Avis::class)->findBy(['isValide' => true]);
    
        return $this->render('pages/avis/avisall.html.twig', [
            'avis_valides' => $avis_valides,
        ]);
    }
}



