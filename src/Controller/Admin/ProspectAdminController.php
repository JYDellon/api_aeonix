<?php

namespace App\Controller\Admin;

use App\Entity\Prospect;
use App\Form\ProspectType;
use Symfony\Component\Mime\Email;
use App\Repository\ProspectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/prospects')]
class ProspectAdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }








    #[Route('/admin/prospects', name: 'admin_prospect_index2', methods: ['GET'])]
    public function pagination(Request $request, PaginatorInterface $paginator): Response
    {
        // Créer une requête avec QueryBuilder pour l'entité Prospect
        $queryBuilder = $this->entityManager
            ->getRepository(Prospect::class)
            ->createQueryBuilder('p');
    
        // Utiliser le service Paginator pour paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder, // La QueryBuilder
            $request->query->getInt('page', 1), // Numéro de page (par défaut : 1)
            10 // Nombre d'éléments par page
        );
    
        // Renvoyer la vue Twig avec la pagination
        return $this->render('prospect/index.html.twig', [
            'pagination' => $pagination, // Passer la variable à Twig
        ]);
    }
    










    
    // #[Route('/', name: 'admin_prospect_index', methods: ['GET'])]
    // public function index(): Response
    // {
    //     $prospects = $this->entityManager->getRepository(Prospect::class)->findAll();

    //     return $this->render('prospect/index.html.twig', [
    //         'prospects' => $prospects,
    //     ]);
    // }







    #[Route('/', name: 'admin_prospect_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->entityManager->getRepository(Prospect::class)->createQueryBuilder('p');
    
        $pagination = $paginator->paginate(
            $queryBuilder, // QueryBuilder ou tableau
            $request->query->getInt('page', 1), // Numéro de la page
            8// Nombre d'éléments par page
        );
    
        return $this->render('prospect/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    


    #[Route('/admin/prospects/send-mail', name: 'prospect_send_mail', methods: ['GET', 'POST'])]
    public function sendMail(Request $request): Response
    {
        return $this->render('prospect/send_mail.html.twig');
    }
    









    
    #[Route('/delete-multiple', name: 'admin_prospect_delete_multiple', methods: ['POST'])]
    public function deleteMultiple(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            return new JsonResponse(['error' => 'Aucun prospect sélectionné.'], 400);
        }

        $prospects = $this->entityManager->getRepository(Prospect::class)->findBy(['id' => $ids]);

        foreach ($prospects as $prospect) {
            $this->entityManager->remove($prospect);
        }
        $this->entityManager->flush();

        return new JsonResponse(['success' => 'Les prospects sélectionnés ont été supprimés avec succès.']);
    }

    #[Route('/send-emails', name: 'admin_prospects_send_emails', methods: ['POST'])]
    public function sendEmails(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $subject = $data['subject'] ?? null;
        $message = $data['message'] ?? null;
        $recipients = $data['recipients'] ?? [];

        if (!$subject || !$message || empty($recipients)) {
            return new JsonResponse(['error' => 'Objet, message ou destinataires manquants.'], 400);
        }

        foreach ($recipients as $recipient) {
            try {
                $email = (new Email())
                    ->from('admin@example.com')
                    ->to($recipient)
                    ->subject($subject)
                    ->html('<p>' . nl2br($message) . '</p>');
                $mailer->send($email);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'error' => 'Erreur lors de l\'envoi des e-mails : ' . $e->getMessage()
                ], 500);
            }
        }

        return new JsonResponse(['success' => 'E-mails envoyés avec succès.']);
    }


    #[Route('/new', name: 'admin_prospect_new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $prospect = new Prospect();
        $form = $this->createForm(ProspectType::class, $prospect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($prospect);
            $this->entityManager->flush();

            $this->addFlash('success', 'Prospect créé avec succès.');
            return $this->redirectToRoute('admin_prospect_index');
        }

        return $this->render('prospect/new.html.twig', [
    'form' => $form->createView(),
        ]);
    }
    
    
#[Route('/prospect/{id}/edit', name: 'prospect_edit', methods: ['GET', 'POST'])]
public function edit(Prospect $prospect, Request $request, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ProspectType::class, $prospect);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        $this->addFlash('success', 'Prospect modifié avec succès.');

        return $this->redirectToRoute('admin_prospect_index'); 
    }

    return $this->render('prospect/edit.html.twig', [
        'form' => $form->createView(),
        'prospect' => $prospect,
    ]);
}

    
//     #[Route('/{id}/edit', name: 'admin_prospect_edit', methods: ['GET', 'POST'])]
// public function edit(Request $request, Prospect $prospect): Response
// {
//     $form = $this->createForm(ProspectType::class, $prospect);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         $this->entityManager->flush();

//         $this->addFlash('success', 'Prospect modifié avec succès.');
//         return $this->redirectToRoute('admin_prospect_index');
//     }

//     return $this->render('admin/prospect/form.html.twig', [
//         'form' => $form->createView(),
//         'prospect' => $prospect,
//     ]);
// }

    
    
    
}