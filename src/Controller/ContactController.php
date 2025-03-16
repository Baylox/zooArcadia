<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, EmailService $emailService): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Validation stricte pour l'email
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL); // Vérifie si l'email est valide et sypprimme les caractères spéciaux
            if (!$email) {
                $this->addFlash('error', 'Adresse email invalide.');
                return $this->redirectToRoute('app_contact');
            }

            // Nettoyer les données
            $titre = htmlspecialchars(($data['titre']), ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars(($data['description']), ENT_QUOTES, 'UTF-8'); 
            
 
                /// Construire l'email
                $text = sprintf(
                    "Nouveau message reçu :\n\nTitre : %s\n\nDescription :\n%s\n\nDe : %s",
                    $titre,
                    $description,
                    $email
                );
            
                $emailService->sendEmail($email, 'arcadiazoodragon@gmail.com', $titre, $text);

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
