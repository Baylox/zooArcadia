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
            $avis->setPseudo(strip_tags($avis->getPseudo()));
            $avis->setCommentaire(strip_tags($avis->getCommentaire()));
            
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
    public function validatedAvis(DocumentManager $dm, Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1)); // Page actuelle (par défaut 1)
        $limit = 9; // Nombre d'avis par page
        $offset = ($page - 1) * $limit; // Décalage pour MongoDB
    
        // Récupère les avis validés avec pagination
        $queryBuilder = $dm->getRepository(Avis::class)->createQueryBuilder()
            ->field('isValide')->equals(true)
            ->skip($offset)
            ->limit($limit);
    
        $avis_valides = $queryBuilder->getQuery()->execute();
    
        // Calcul du nombre total d'avis validés
        $totalAvis = $dm->getRepository(Avis::class)->createQueryBuilder()
            ->field('isValide')->equals(true)
            ->count()
            ->getQuery()
            ->execute();
    
        $totalPages = ceil($totalAvis / $limit); // Calcul du nombre total de pages
    
        return $this->render('pages/avis/avisall.html.twig', [
            'avis_valides' => $avis_valides,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}



