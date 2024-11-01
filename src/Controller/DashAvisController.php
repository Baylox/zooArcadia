<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Avis;

class DashAvisController extends AbstractController
{

    #[Route('/dash/avis', name: 'app_dash_avis')]
    public function index(DocumentManager $dm): Response
    {
        $avis = $dm->getRepository(Avis::class)->findAll();

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }
    
    // Valider ou rejeter un avis
    #[Route('/dash/avis/{id}/validate', name: 'app_dash_avis_validate', methods: ['POST'])]
    public function validate(string $id, DocumentManager $dm): Response
    {
        $avis = $dm->getRepository(Avis::class)->find($id);
        if (!$avis) {
            throw $this->createNotFoundException('Avis non trouvé');
        }

        $avis->setIsValide(true);
        $dm->flush();

        return $this->redirectToRoute('app_dash_avis');
    }

    // Supprimer un avis
    #[Route('/dash/avis/{id}/delete', name: 'app_dash_avis_delete', methods: ['POST'])]
    public function delete(string $id, DocumentManager $dm): Response
    {
        $avis = $dm->getRepository(Avis::class)->find($id);
        if (!$avis) {
            throw $this->createNotFoundException('Avis non trouvé');
        }

        $dm->remove($avis);
        $dm->flush();

        return $this->redirectToRoute('app_dash_avis');
    }
}

