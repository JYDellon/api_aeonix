<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * Affiche le formulaire d'envoi d'emails.
     */
    #[Route('/email/form', name: 'email_form', methods: ['GET'])]
    public function emailForm(Request $request): Response
    {
        $recipients = $request->query->get('recipients', '');
        return $this->render('email/send_mail.html.twig', [
            'recipients' => $recipients,
        ]);
    }

    /**
     * Traite l'envoi d'emails et redirige vers une page de résultat.
     */
    #[Route('/api/send-email', name: 'send_email', methods: ['POST'])]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        try {
            $recipients = $request->request->get('recipients', '');
            $subject = $request->request->get('subject', 'Aucun objet');
            $message = $request->request->get('message', '');
            $attachments = $request->files->get('attachments');

            if (empty($recipients)) {
                return $this->redirectToRoute('message_result', [
                    'title' => 'Erreur',
                    'message' => 'Aucun destinataire spécifié.',
                ]);
            }

            $recipients = is_array($recipients) ? $recipients : explode(',', $recipients);
            $validRecipients = array_filter(array_map('trim', $recipients), function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            if (empty($validRecipients)) {
                return $this->redirectToRoute('message_result', [
                    'title' => 'Erreur',
                    'message' => 'Aucun destinataire valide.',
                ]);
            }

            foreach ($validRecipients as $recipient) {
                $email = (new Email())
                    ->from('votre.email@domaine.com')
                    ->to($recipient)
                    ->subject($subject)
                    ->text($message);

                if ($attachments) {
                    foreach ($attachments as $attachment) {
                        if ($attachment->isValid()) {
                            $email->attachFromPath(
                                $attachment->getPathname(),
                                $attachment->getClientOriginalName()
                            );
                        }
                    }
                }

                $mailer->send($email);
                usleep(200000);
            }

            return $this->redirectToRoute('message_result', [
                'message' => 'Emails envoyés avec succès.',
            ]);
        } catch (\Exception $e) {
            return $this->redirectToRoute('message_result', [
                'title' => 'Erreur',
                'message' => 'Une erreur est survenue lors de l\'envoi des emails.',
            ]);
        }
    }

    /**
     * Affiche le message de résultat.
     */
    #[Route('/message/result', name: 'message_result', methods: ['GET'])]
    public function messageResult(Request $request): Response
    {
        $title = $request->query->get('title', '');
        $message = $request->query->get('message', '');

        return $this->render('email/message_result.html.twig', [
            'title' => $title,
            'message' => $message,
        ]);
    }
}