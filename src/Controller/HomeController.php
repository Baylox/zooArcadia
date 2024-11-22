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

        return $this->render('home/index.html.twig', [
            'horaires' => $horaires,
        ]);
    }
}


