<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Form\ProspectType;
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
use Knp\Component\Pager\PaginatorInterface;

class ProspectController extends AbstractController
{
    private LoggerInterface $logger;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    
    

    // #[Route('/admin/prospects', name: 'admin_prospect_index2', methods: ['GET'])]
    // public function pagination(Request $request, PaginatorInterface $paginator): Response
    // {
    //     $queryBuilder = $this->entityManager->getRepository(Prospect::class)->createQueryBuilder('p');
    
    //     $prospects = $paginator->paginate(
    //         $queryBuilder, /* QueryBuilder ou tableau */
    //         $request->query->getInt('page', 1), /* Numéro de la page */
    //         8 /* Nombre d'éléments par page */
    //     );
    
    //     return $this->render('prospect/index.html.twig', [
    //         'prospects' => $prospects,
    //     ]);
    // }
    

    #[Route('/prospects', name: 'admin_prospect_index2')]
    public function pagination(
        Request $request,
        ProspectRepository $prospectRepository,
        PaginatorInterface $paginator
    ): Response {
        // Créer une requête avec le QueryBuilder
        $queryBuilder = $prospectRepository->createQueryBuilder('p');
    
        // Gérer la pagination
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Numéro de la page actuelle (par défaut 1)
            8 // Nombre d'éléments par page
        );
    
        // Rendre la vue avec la pagination
        return $this->render('prospects/index.html.twig', [
            'pagination' => $pagination, // Utilisez cette variable dans Twig
        ]);
    }
    
    

    #[Route('/admin/prospects', name: 'admin_prospect_index', methods: ['GET'])]
    public function index(): Response
    {
        $prospects = $this->entityManager->getRepository(Prospect::class)->findAll();

        return $this->render('prospect/index.html.twig', [
            'prospects' => $prospects,
        ]);
    }


    #[Route('/admin/prospects/delete', name: 'admin_prospect_delete_individual', methods: ['DELETE'])]
    public function deleteIndividualProspect(Request $request, ProspectRepository $prospectRepository): JsonResponse
    {
        $id = $request->query->get('id'); // Récupérer l'identifiant depuis la requête
    
        if (!$id) {
            return new JsonResponse(['error' => 'ID manquant.'], 400);
        }
    
        $prospect = $prospectRepository->find($id);
    
        if (!$prospect) {
            return new JsonResponse(['error' => 'Prospect introuvable.'], 404);
        }
    
        try {
            $this->entityManager->remove($prospect);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()], 500);
        }
    
        return new JsonResponse(['success' => 'Prospect supprimé avec succès.'], 200);
    }
    
    
    
    #[Route('/admin/prospects/delete-multiple', name: 'admin_prospect_delete_multiple', methods: ['POST'])]
    public function deleteMultiple(Request $request, ProspectRepository $prospectRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return new JsonResponse(['error' => 'Aucun prospect sélectionné.'], 400);
        }

        $prospects = $prospectRepository->findBy(['id' => $ids]);

        if (empty($prospects)) {
            return new JsonResponse(['error' => 'Aucun prospect correspondant trouvé.'], 404);
        }

        foreach ($prospects as $prospect) {
            $this->entityManager->remove($prospect);
        }

        $this->entityManager->flush();

        return new JsonResponse(['success' => 'Les prospects sélectionnés ont été supprimés avec succès.']);
    }

    
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


    #[Route('/prospect', name: 'prospect_index')]
    public function prospectRedirect(): Response
    {
        return $this->redirectToRoute('prospect_index');
    }
    
    

    
    
    
    
    #[Route('/prospect/new', name: 'prospect_new', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prospect);
            $entityManager->flush();
    
            $this->addFlash('success', 'Prospect créé avec succès.');
    
            return $this->redirectToRoute('admin_prospect_index'); 
        }
    
        return $this->render('prospect/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    





// #[Route('/prospect/{id}/edit', name: 'prospect_edit', methods: ['GET', 'POST'])]
// public function edit(Prospect $prospect, Request $request, EntityManagerInterface $entityManager): Response
// {
//     $form = $this->createForm(ProspectType::class, $prospect);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $entityManager->flush();

//         return $this->redirectToRoute('admin_prospect_index'); 
//     }

//     return $this->render('prospect/form.html.twig', [
//         'form' => $form->createView(),
//         'prospect' => $prospect,
//     ]);
// }












#[Route('/send-mail', name: 'send_mail', methods: ['POST'])]
    public function sendMail(Request $request, MailerInterface $mailer): Response
    {
        $recipients = $request->request->get('recipients');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');

        if (!$recipients || !$subject || !$message) {
            $this->addFlash('error', 'Tous les champs sont obligatoires.');
            return $this->redirectToRoute('prospects_list');
        }

        $emails = explode(',', $recipients);
        foreach ($emails as $email) {
            $emailMessage = (new Email())
                ->from('your-email@example.com')
                ->to($email)
                ->subject($subject)
                ->text($message);

            $mailer->send($emailMessage);
        }

        $this->addFlash('success', 'Les e-mails ont été envoyés avec succès.');
        return $this->redirectToRoute('prospects_list');
    }














    #[Route('/admin/prospects/delete', name: 'admin_prospect_delete', methods: ['POST'])]
    public function deleteProspects(
        Request $request,
        ProspectRepository $prospectRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Récupérer les IDs depuis la requête JSON
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return new JsonResponse(['error' => 'Aucun prospect sélectionné.'], 400);
        }

        // Trouver les prospects correspondants
        $prospects = $prospectRepository->findBy(['id' => $ids]);

        if (empty($prospects)) {
            return new JsonResponse(['error' => 'Aucun prospect correspondant trouvé.'], 404);
        }

        // Supprimer les prospects
        foreach ($prospects as $prospect) {
            $entityManager->remove($prospect);
        }
        $entityManager->flush();

        return new JsonResponse(['success' => 'Les prospects ont été supprimés avec succès.']);
    }





    #[Route('/admin/prospects/send-emails', name: 'admin_prospects_send_emails', methods: ['POST'])]
    public function sendEmails(Request $request, ProspectRepository $prospectRepository, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $subject = $data['subject'] ?? null;
        $message = $data['message'] ?? null;
        $recipients = $data['recipients'] ?? [];

        if (!$subject || !$message || empty($recipients)) {
            return new JsonResponse(['error' => 'Veuillez fournir un objet, un message, et des destinataires valides.'], 400);
        }

        $emails = $prospectRepository->findEmailsByIds($recipients);

        foreach ($emails as $email) {
            $emailMessage = (new Email())
                ->from('admin@example.com')
                ->to($email['email'])
                ->subject($subject)
                ->text($message)
                ->html('<p>' . nl2br($message) . '</p>');

            $mailer->send($emailMessage);
        }

        return new JsonResponse(['success' => true]);
    }


    

    #[Route('/admin/prospect/get-emails', name: 'admin_prospect_get_emails', methods: ['POST'])]
    public function getEmails(Request $request, ProspectRepository $prospectRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return new JsonResponse(['error' => 'Aucun ID fourni.'], 400);
        }

        $emails = $prospectRepository->findEmailsByIds($ids);

        return new JsonResponse(['emails' => array_column($emails, 'email')]);
    }

    

    







    
}