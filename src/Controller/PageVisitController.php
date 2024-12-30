<?php

namespace App\Controller;

use App\Entity\PageVisit;
use App\Repository\PageVisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageVisitController extends AbstractController
{
    /**
     * Enregistrer une visite pour une page donnée
     */
    // #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST'])]
    // public function recordVisit(
    //     string $pageUrl,
    //     PageVisitRepository $repository,
    //     EntityManagerInterface $entityManager
    // ): JsonResponse {
    //     // Normalisation de l'URL
    //     $pageUrl = rtrim(strtolower($pageUrl), '/');
    
    //     try {
    //         // Vérifier si la page existe déjà
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount(); // Incrémentation du compteur
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         $response = new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ]);
    
    //         // Ajout des en-têtes CORS ou autres
    //         $response->headers->set('Access-Control-Allow-Origin', '*');
    //         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    
    //         return $response;
    //     } catch (\Exception $e) {
    //         $response = new JsonResponse([
    //             'message' => 'Erreur lors de l\'enregistrement de la visite.',
    //             'error' => $e->getMessage(),
    //         ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    
    //         // Ajout des en-têtes à la réponse d'erreur
    //         $response->headers->set('Access-Control-Allow-Origin', '*');
    //         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    
    //         return $response;
    //     }
    // }
    















    #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    public function recordVisit(
        string $pageUrl,
        PageVisitRepository $repository,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        // Ajout des en-têtes CORS pour toutes les requêtes
        $allowedOrigin = 'https://aeonix-blue.vercel.app';
    
        // Gérer les requêtes OPTIONS (prévol)
        if ($request->getMethod() === 'OPTIONS') {
            $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
            $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
    
            return $response;
        }
    
        // Traitement des requêtes POST
        $pageUrl = rtrim(strtolower($pageUrl), '/');
    
        try {
            // Vérifier si la page existe déjà
            $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
            $pageVisit->setPageUrl($pageUrl);
            $pageVisit->incrementVisitCount();
    
            $entityManager->persist($pageVisit);
            $entityManager->flush();
    
            $response = new JsonResponse([
                'message' => 'Visite enregistrée avec succès.',
                'pageUrl' => $pageVisit->getPageUrl(),
                'visitCount' => $pageVisit->getVisitCount(),
            ]);
    
            // Ajout des en-têtes CORS à la réponse de succès
            $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
    
            return $response;
        } catch (\Exception $e) {
            $response = new JsonResponse([
                'message' => 'Erreur lors de l\'enregistrement de la visite.',
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    
            // Ajout des en-têtes à la réponse d'erreur
            $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
    
            return $response;
        }
    }
    












    

    /**
     * Récupérer toutes les visites enregistrées
     */
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

    /**
     * Réinitialiser toutes les visites enregistrées
     */
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
}