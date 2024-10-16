<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;


class EmailService
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendWelcomeEmail(string $userEmail): void
    {
        $email = (new Email())
            ->from('test@example.com')
            ->to($userEmail)
            ->subject('Bienvenue au sein de l\'équipe d\'Arcadia !')
            ->html('<p>Bienvenue sur notre site ! Nous sommes ravis de vous compter parmi nous.</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Log the error message
            $this->logger->error('Un problème est survenu', [
                'exception' => $e,
                'email' => $userEmail,
            ]);
        }
    }
}