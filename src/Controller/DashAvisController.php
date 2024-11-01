<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Avis;

class DashAvisController extends AbstractController
{
    #[Route('/dash/avis', name: 'dashboard_avis')]
    public function index(DocumentManager $dm, Request $request): Response
    {
        // Récupère le paramètre 'status' de la requête pour filtrer les avis
        $status = $request->query->get('status', 'pending'); // pending pour afficher les avis en attente de validation

        if ($status === 'validated') {
            $avis = $dm->getRepository(Avis::class)->findBy(['isValide' => true]);
        } else {
            $avis = $dm->getRepository(Avis::class)->findBy(['isValide' => false]);
        }

        return $this->render('dashboard/avis/index.html.twig', [
            'avis' => $avis,
            'status' => $status,
        ]);
    }


    #[Route('/dash/avis/{id}/validate', name: 'dashboard_avis_validate', methods: ['POST'])]
    public function validate(string $id, DocumentManager $dm): Response
    {
        $avis = $dm->getRepository(Avis::class)->find($id);
        if (!$avis) {
            throw $this->createNotFoundException('Avis non trouvé');
        }

        $avis->setIsValide(true);
        $dm->flush();

        return $this->redirectToRoute('dashboard_avis');
    }

    #[Route('/dash/avis/{id}/delete', name: 'dashboard_avis_delete', methods: ['POST'])]
    public function delete(string $id, DocumentManager $dm): Response
    {
        $avis = $dm->getRepository(Avis::class)->find($id);
        if (!$avis) {
            throw $this->createNotFoundException('Avis non trouvé');
        }

        $dm->remove($avis);
        $dm->flush();

        return $this->redirectToRoute('dashboard_avis');
    }
}


