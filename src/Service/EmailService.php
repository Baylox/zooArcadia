<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
        $email = (new TemplatedEmail())
            ->from('José@arcadia.com')
            ->to($userEmail)
            ->subject('Bienvenue au sein de l\'équipe d\'Arcadia !')
            ->htmlTemplate('emails/welcome.html.twig');

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

    public function sendEmail(string $from, string $to, string $subject, string $text): void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($text);

        $this->mailer->send($email);
    }
}