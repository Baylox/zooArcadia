<?php

namespace App\Controller;

use App\Document\Horaire;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DocumentManager $dm): Response
    {
        $horaires = $dm->getRepository(Horaire::class)->findAll();
    
        // Ordre des jours de la semaine
        $joursOrdre = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    
        // Trier les horaires selon l'ordre des jours de la semaine
        usort($horaires, function ($a, $b) use ($joursOrdre) {
            return array_search($a->getJour(), $joursOrdre) - array_search($b->getJour(), $joursOrdre);
        });
    
        return $this->render('home/index.html.twig', [
            'horaires' => $horaires,
        ]);
    }    
}


