<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Validation stricte pour l'email
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL); // Vérifie si l'email est valide
            if (!$email) {
                $this->addFlash('error', 'Adresse email invalide.');
                return $this->redirectToRoute('app_contact');
            }

            // Nettoyer les données
            $titre = strip_tags($data['titre']); 
            $description = strip_tags($data['description']); 
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL); // Supprime les caractères non valides
 
                /// Construire l'email
                $email = (new Email())
                ->from($email)
                ->to('zoo@arcadia.fr')
                ->subject($titre)
                ->text(sprintf(
                    "Nouveau message reçu :\n\nTitre : %s\n\nDescription :\n%s\n\nDe : %s",
                    $titre,
                    $description,
                    $email
                ));
            
            $mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
