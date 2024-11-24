<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Avis;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYE')]
class DashAvisController extends AbstractController
{
    // Affiche les avis en attente ou validés
    #[Route('/dash/avis', name: 'dashboard_avis')]
    public function index(DocumentManager $dm, Request $request): Response
    {
        $status = $request->query->get('status', 'pending'); // Par défaut, afficher les avis en attente
        $page = max(1, $request->query->getInt('page', 1)); // Page actuelle, par défaut 1
        $limit = 10; // Nombre d'avis par pageS

        // Calcule combien d'avis sauter pour atteindre la page actuelle
        $offset = ($page - 1) * $limit;

        // Filtrer les avis selon le statut 
        $isValidated = $status === 'validated';
        $queryBuilder = $dm->getRepository(Avis::class)->createQueryBuilder()
            ->field('isValide')->equals($isValidated)
            ->skip($offset) // Définit le décalage pour la pagination
            ->limit($limit) // Limite le nombre d'avis à récupérer
            ->getQuery();

        // Exécute la requête pour récupérer les avis correspondant à la page actuelle
        $avis = $queryBuilder->execute();

        // Calculer le nombre total d'avis pour savoir combien de pages il y a
        $totalAvis = $dm->getRepository(Avis::class)->createQueryBuilder()
            ->field('isValide')->equals($isValidated)
            ->count() // Compte le nombre total d'avis correspondant
            ->getQuery()
            ->execute();

        // Calcule le nombre total de pages nécessaires en fonction du nombre total d'avis et de la limite par page
        $totalPages = ceil($totalAvis / $limit);

        return $this->render('dashboard/avis/index.html.twig', [
            'avis' => $avis,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    // Validation d'un avis
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


