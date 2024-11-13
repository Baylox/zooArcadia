<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_EMPLOYE')]
class DashEmployeController extends AbstractController
{   
    #[Route('/dash/employe', name: 'app_dash_employe')]
    public function index(): Response
    {
        return $this->render('dashboard/menudashboard/index.html.twig', [
            'controller_name' => 'DashEmployeController',
        ]);
    }
}
