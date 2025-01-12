<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $recipient, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('jy.dellon@gmail.com') // Changez pour l'adresse expéditrice correcte
            ->to($recipient)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}