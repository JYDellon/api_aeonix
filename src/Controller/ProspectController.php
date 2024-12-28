<?php

namespace App\Controller;

use App\Entity\Prospect;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\ProspectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProspectController extends AbstractController
{
    private LoggerInterface $logger;

    #[Route('/api/contact', name: 'api_contact', methods: ['POST'])]
    public function handleContact(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
    
        // Validation des champs requis
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['message'])) {
            return new JsonResponse([
                'error' => 'Les champs nom, prénom, email et message sont obligatoires.'
            ], 400);
        }
    
        // Création du prospect
        $prospect = new Prospect();
        $prospect->setNom($data['nom']);
        $prospect->setPrenom($data['prenom']);
        $prospect->setEmail($data['email']);
        $prospect->setTelephone($data['telephone'] ?? null);
        $prospect->setEntreprise($data['entreprise'] ?? null);
        $prospect->setClient($data['client'] ?? false);
    
        try {
            // Sauvegarde du prospect en base de données
            $entityManager->persist($prospect);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'enregistrement en base de données : ' . $e->getMessage()
            ], 500);
        }
    
        try {
            // Email envoyé à l'administrateur
            $adminEmail = (new Email())
                ->from('jy.dellon@gmail.com')
                ->replyTo($prospect->getEmail())
                ->to('jy.dellon@gmail.com')
                ->subject('Nouveau message du formulaire de contact')
                ->text(
                    "Prénom : {$prospect->getPrenom()}\n" .
                    "Nom : {$prospect->getNom()}\n" .
                    "Entreprise : {$prospect->getEntreprise()}\n\n" .
                    "Email : {$prospect->getEmail()}\n" .
                    "Téléphone : {$prospect->getTelephone()}\n\n" .
                    "Message : {$data['message']}\n"
                );
    
            $mailer->send($adminEmail);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'envoi de l\'e-mail au gestionnaire : ' . $e->getMessage()
            ], 500);
        }
    
        try {
            // Email de confirmation envoyé au prospect
            $confirmationEmail = (new Email())
                ->from('contact.aeonix@gmail.com')
                ->to($prospect->getEmail())
                ->subject('Confirmation de réception')
                ->text(
                    "Bonjour {$prospect->getPrenom()},\n\n" .
                    "Nous avons bien reçu le message suivant, de votre part :\n\n" .
                    "{$data['message']}\n\n" .
                    "Nous vous répondrons dans les plus brefs délais.\n\n" .
                    "Merci de nous avoir contactés !\n\n" .
                    "Cordialement,\nL'équipe."
                );
    
            $mailer->send($confirmationEmail);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'envoi de l\'e-mail de confirmation : ' . $e->getMessage()
            ], 500);
        }
    
        return new JsonResponse(['message' => 'Demande enregistrée et e-mails envoyés avec succès.'], 200);
    }
    


    #[Route('/api/devis', name: 'api_devis', methods: ['POST'])]
    public function handleDevis(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // Validation des champs requis
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email'])) {
            return new JsonResponse(['error' => 'Les champs nom, prénom et email sont obligatoires.'], 400);
        }

        // Création d'un nouveau prospect
        $prospect = new Prospect();
        $prospect->setNom($data['nom']);
        $prospect->setPrenom($data['prenom']);
        $prospect->setEmail($data['email']);
        $prospect->setTelephone($data['telephone'] ?? null);
        $prospect->setEntreprise($data['nomEntreprise'] ?? null);

        try {
            $entityManager->persist($prospect);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'enregistrement en base de données : ' . $e->getMessage()
            ], 500);
        }

        try {
            // Construction dynamique du corps de l'e-mail
            $emailBody = "Une nouvelle demande de devis a été soumise :\n\n";
            $emailBody .= "Nom : {$data['nom']}\n";
            $emailBody .= "Prénom : {$data['prenom']}\n\n";

            if (!empty($data['nomEntreprise'])) {
                $emailBody .= "Entreprise : {$data['nomEntreprise']}\n\n";
            }

            $emailBody .= "Email : {$data['email']}\n";

            if (!empty($data['telephone'])) {
                $emailBody .= "Téléphone : {$data['telephone']}\n\n";
            }

            if (!empty($data['typeProjet'])) {
                $emailBody .= "Type de projet : {$data['typeProjet']}\n";
            }

            if (!empty($data['objectifs'])) {
                $emailBody .= "Objectifs : {$data['objectifs']}\n";
            }

            if (!empty($data['descriptionBesoins'])) {
                $emailBody .= "Description des besoins : {$data['descriptionBesoins']}\n";
            }

            if (!empty($data['urlSite'])) {
                $emailBody .= "URL du site : {$data['urlSite']}\n";
            }

            if (!empty($data['fonctionnalites'])) {
                $emailBody .= "Fonctionnalités demandées : {$data['fonctionnalites']}\n";
            }

            if (!empty($data['graphisme'])) {
                $emailBody .= "Graphisme : {$data['graphisme']}\n";
            }

            if (!empty($data['urlSiteRefonte'])) {
                $emailBody .= "URL pour une refonte : {$data['urlSiteRefonte']}\n";
            }

            if (!empty($data['besoinsHebergement'])) {
                $emailBody .= "Besoins spécifiques : {$data['besoinsHebergement']}\n";
            }

            // Ajout des nouvelles informations
            if (!empty($data['domaineOption'])) {
                $emailBody .= "Option Domaine / Hébergement : {$data['domaineOption']}\n";
            }

            if (!empty($data['maintenanceOption'])) {
                $emailBody .= "Option Maintenance : {$data['maintenanceOption']}\n";
            }

            if (!empty($data['langueOption'])) {
                $emailBody .= "Langues disponibles : {$data['langueOption']}\n";
            }

            // Email à l'administrateur
            $adminEmail = (new Email())
                ->from('contact.aeonix@gmail.com')
                ->replyTo($prospect->getEmail())
                ->to('jy.dellon@gmail.com')
                ->subject('Nouvelle demande de devis')
                ->text($emailBody);

            $mailer->send($adminEmail);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'envoi de l\'e-mail au gestionnaire : ' . $e->getMessage()
            ], 500);
        }

        try {
            // Construction de l'email de confirmation au prospect
            $confirmationBody = "Bonjour {$data['prenom']},\n\n";
            $confirmationBody .= "Nous avons bien reçu votre demande de devis pour la prestation suivante :\n\n";

            if (!empty($data['typeProjet'])) {
                $confirmationBody .= "Type de projet : {$data['typeProjet']}\n";
            }

            if (!empty($data['objectifs'])) {
                $confirmationBody .= "Objectifs : {$data['objectifs']}\n";
            }

            if (!empty($data['prestation'])) {
                $confirmationBody .= "Prestation : {$data['prestation']}\n";
            }

            // Ajout des nouvelles informations dans l'email de confirmation
            if (!empty($data['domaineOption'])) {
                $confirmationBody .= "Option Domaine / Hébergement : {$data['domaineOption']}\n";
            }

            if (!empty($data['maintenanceOption'])) {
                $confirmationBody .= "Option Maintenance : {$data['maintenanceOption']}\n";
            }

            if (!empty($data['langueOption'])) {
                $confirmationBody .= "Langues disponibles : {$data['langueOption']}\n";
            }

            $confirmationBody .= "\nNous vous répondrons dans les plus brefs délais.\n\n";
            $confirmationBody .= "Merci de nous avoir contactés !\n\nCordialement,\nL'équipe.";

            $confirmationEmail = (new Email())
                ->from('contact.aeonix@gmail.com')
                ->to($prospect->getEmail())
                ->subject('Confirmation de demande de devis')
                ->text($confirmationBody);

            $mailer->send($confirmationEmail);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'envoi de l\'e-mail de confirmation : ' . $e->getMessage()
            ], 500);
        }

        return new JsonResponse(['message' => 'Demande de devis envoyée avec succès et prospect enregistré.'], 200);
    }

    #[Route('/api/prospects', name: 'api_prospects_list', methods: ['GET'])]
    public function listProspects(EntityManagerInterface $entityManager): JsonResponse
    {
        $repository = $entityManager->getRepository(Prospect::class);
        $prospects = $repository->findAll();
    
        $data = array_map(function (Prospect $prospect) {
            return [
                'id' => $prospect->getId(),
                'nom' => $prospect->getNom(),
                'prenom' => $prospect->getPrenom(),
                'email' => $prospect->getEmail(),
                'telephone' => $prospect->getTelephone(),
                'entreprise' => $prospect->getEntreprise(),
                'client' => $prospect->isClient(),
            ];
        }, $prospects);
    
        return new JsonResponse($data, 200);
    }

    #[Route('/api/prospects', name: 'api_prospects_create', methods: ['POST'])]
    public function createProspect(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email'])) {
            return new JsonResponse(['error' => 'Les champs nom, prénom et email sont obligatoires.'], 400);
        }

        $prospect = new Prospect();
        $prospect->setNom($data['nom']);
        $prospect->setPrenom($data['prenom']);
        $prospect->setEmail($data['email']);
        $prospect->setTelephone($data['telephone'] ?? null);
        $prospect->setEntreprise($data['entreprise'] ?? null);
        $prospect->setClient($data['client'] ?? false);

        try {
            $entityManager->persist($prospect);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => 'Prospect créé avec succès.'], 201);
    }

    #[Route('/api/prospect/{id}', name: 'api_prospect_update', methods: ['PUT'])]
    public function updateProspect(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $repository = $entityManager->getRepository(Prospect::class);
        $prospect = $repository->find($id);

        if (!$prospect) {
            return new JsonResponse(['error' => 'Prospect introuvable'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $prospect->setNom($data['nom'] ?? $prospect->getNom());
        $prospect->setPrenom($data['prenom'] ?? $prospect->getPrenom());
        $prospect->setEmail($data['email'] ?? $prospect->getEmail());
        $prospect->setTelephone($data['telephone'] ?? $prospect->getTelephone());
        $prospect->setEntreprise($data['entreprise'] ?? $prospect->getEntreprise());
        $prospect->setClient($data['client'] ?? $prospect->isClient());

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => 'Prospect mis à jour avec succès.'], 200);
    }

    #[Route('/api/prospect/{id}', name: 'api_prospect_delete', methods: ['DELETE'])]
    public function deleteProspect(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $repository = $entityManager->getRepository(Prospect::class);
        $prospect = $repository->find($id);

        if (!$prospect) {
            return new JsonResponse(['error' => 'Prospect introuvable'], 404);
        }

        try {
            $entityManager->remove($prospect);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()], 500);
        }

        return new JsonResponse(['message' => 'Prospect supprimé avec succès.'], 200);
    }

    #[Route('/api/prospects/delete', name: 'delete_multiple_prospects', methods: ['POST'])]
    public function deleteMultiple(
        Request $request,
        ProspectRepository $prospectRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return new JsonResponse(['error' => 'Aucun identifiant fourni pour la suppression.'], 400);
        }

        // Récupérer les prospects correspondants
        $prospects = $prospectRepository->findBy(['id' => $ids]);

        if (empty($prospects)) {
            return new JsonResponse(['error' => 'Aucun prospect trouvé pour ces identifiants.'], 404);
        }

        // Supprimer les prospects
        foreach ($prospects as $prospect) {
            $entityManager->remove($prospect);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Prospects supprimés avec succès.'], 200);
    }

    #[Route('/api/send-emails', name: 'send_emails', methods: ['POST'])]
    public function sendEmails(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $recipients = $data['recipients'] ?? [];
        $subject = $data['subject'] ?? '';
        $body = $data['body'] ?? '';
        $attachments = $data['attachments'] ?? [];

        if (empty($recipients) || empty($subject) || empty($body)) {
            return new JsonResponse(['error' => 'Données manquantes (recipients, subject, body).'], 400);
        }

        try {
            foreach ($recipients as $recipient) {
                $email = (new Email())
                    ->from('your-email@example.com')
                    ->to($recipient)
                    ->subject($subject)
                    ->html($body);

                foreach ($attachments as $attachment) {
                    $email->attach(
                        base64_decode($attachment['content']),
                        $attachment['name'],
                        $attachment['type']
                    );
                }

                $mailer->send($email);
            }

            return new JsonResponse(['message' => 'Emails envoyés avec succès.']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de l\'envoi des emails: ' . $e->getMessage()], 500);
        }
    }



}