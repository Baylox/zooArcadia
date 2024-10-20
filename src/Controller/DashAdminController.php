<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashAdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/dash/admin', name: 'app_dash_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'DashAdminController',
        ]);  
    }
}
