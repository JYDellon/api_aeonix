<?php






namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
#[Route('/api/send-email', name: 'send_email', methods: ['POST'])]
public function sendEmail(Request $request, MailerInterface $mailer): JsonResponse
{
try {
$subject = $request->get('subject');
$body = $request->get('body');
$recipients = $request->get('recipients');
$attachments = $request->files->get('attachments');

if (!$recipients || empty($recipients)) {
return new JsonResponse(['error' => 'Aucun destinataire spécifié.'], 400);
}

// Création de l'email
$email = (new Email())
->from('votre.email@domaine.com') // Remplacez par votre adresse
->subject($subject)
->text($body);

foreach ($recipients as $recipient) {
$email->addTo($recipient);
}

// Ajout des pièces jointes
if ($attachments) {
foreach ($attachments as $attachment) {
$email->attachFromPath(
$attachment->getPathname(),
$attachment->getClientOriginalName()
);
}
}

// Envoi de l'email
$mailer->send($email);

return new JsonResponse(['message' => 'Emails envoyés avec succès.'], 200);
} catch (\Exception $e) {
return new JsonResponse([
'error' => 'Erreur lors de l\'envoi des emails : ' . $e->getMessage()
], 500);
}
}
}