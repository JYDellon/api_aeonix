<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendEmailMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendEmailMessage $message)
    {
        // Création de l'e-mail à partir des données du message
        $email = (new Email())
            ->from($message->getFrom())    // Adresse de l'expéditeur
            ->to($message->getTo())        // Adresse du destinataire
            ->subject($message->getSubject()) // Sujet de l'e-mail
            ->html($message->getBody());   // Contenu HTML

        // Envoi de l'e-mail
        $this->mailer->send($email);
    }
}