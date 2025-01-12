<?php

namespace App\Controller;

use App\Entity\PageVisit;
use App\Repository\PageVisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageVisitController extends AbstractController
{
    #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    public function recordVisit(
        string $pageUrl,
        PageVisitRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Normalisation de l'URL
        $pageUrl = rtrim(strtolower($pageUrl), '/');

        // Vérifie si c'est une requête OPTIONS (pré-vol CORS)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT, [
                'Access-Control-Allow-Origin' => 'http://localhost:3000',  // L'origine spécifique
                'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type',
            ]);
        }

        try {
            // Vérifier si la page existe déjà
            $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
            $pageVisit->setPageUrl($pageUrl);
            $pageVisit->incrementVisitCount(); // Incrémentation du compteur

            $entityManager->persist($pageVisit);
            $entityManager->flush();

            // Réponse JSON avec en-têtes CORS
            return new JsonResponse([
                'message' => 'Visite enregistrée avec succès.',
                'pageUrl' => $pageVisit->getPageUrl(),
                'visitCount' => $pageVisit->getVisitCount(),
            ], JsonResponse::HTTP_OK, [
                'Access-Control-Allow-Origin' => 'http://localhost:3000',
                'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        } catch (\Exception $e) {
            // Réponse d'erreur avec en-têtes CORS
            return new JsonResponse([
                'message' => 'Erreur lors de l\'enregistrement de la visite.',
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        }
    }

    #[Route('/api/visit', name: 'api_get_visits', methods: ['GET'])]
    public function getVisits(PageVisitRepository $repository): JsonResponse
    {
        $visits = $repository->findAll();

        $data = array_map(function (PageVisit $visit) {
            return [
                'pageUrl' => $visit->getPageUrl(),
                'visitCount' => $visit->getVisitCount(),
            ];
        }, $visits);

        return new JsonResponse($data);
    }

    #[Route('/api/visit', name: 'api_reset_visits', methods: ['DELETE'])]
    public function resetVisits(PageVisitRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $visits = $repository->findAll();

        foreach ($visits as $visit) {
            $entityManager->remove($visit);
        }

        $entityManager->flush();

        return new JsonResponse(['message' => 'Toutes les visites ont été réinitialisées.']);
    }

    #[Route('/admin/page-visits/{id}', name: 'admin_page_visit_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, PageVisit $pageVisit, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $pageVisit->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($pageVisit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_page_visit_index');
    }

    #[Route('/admin/page-visits', name: 'admin_page_visit_index', methods: ['GET'])]
    public function index(PageVisitRepository $repository): Response
    {
        $visits = $repository->findAll();

        return $this->render('admin/page_visits.html.twig', [
            'visits' => $visits,
        ]);
    }
}