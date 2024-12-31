<?php

namespace App\Controller;

use App\Entity\PageVisit;
use App\Repository\PageVisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;


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

    //         return new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ]);
    //     } catch (\Exception $e) {
    //         return new JsonResponse([
    //             'message' => 'Erreur lors de l\'enregistrement de la visite.',
    //             'error' => $e->getMessage(),
    //         ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
















    // #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    // public function recordVisit(
    //     string $pageUrl,
    //     PageVisitRepository $repository,
    //     EntityManagerInterface $entityManager
    // ): JsonResponse {
    //     // Normalisation de l'URL
    //     $pageUrl = rtrim(strtolower($pageUrl), '/');
    
    //     // Vérifie si c'est une requête OPTIONS (pré-vol CORS)
    //     if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    //         return new JsonResponse(null, Response::HTTP_NO_CONTENT, [
    //             'Access-Control-Allow-Origin' => 'https://aeonix-lake.vercel.app',
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //             'Access-Control-Allow-Credentials' => 'true',
    //         ]);
    //     }
    
    //     try {
    //         // Vérifier si la page existe déjà
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount(); // Incrémentation du compteur
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         // Réponse JSON avec en-têtes CORS
    //         return new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ], JsonResponse::HTTP_OK, [
    //             'Access-Control-Allow-Origin' => 'https://aeonix-lake.vercel.app',
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //             'Access-Control-Allow-Credentials' => 'true',
    //         ]);
    //     } catch (\Exception $e) {
    //         // Réponse d'erreur avec en-têtes CORS
    //         return new JsonResponse([
    //             'message' => 'Erreur lors de l\'enregistrement de la visite.',
    //             'error' => $e->getMessage(),
    //         ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, [
    //             'Access-Control-Allow-Origin' => 'https://aeonix-lake.vercel.app',
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //             'Access-Control-Allow-Credentials' => 'true',
    //         ]);
    //     }
    // }
    





    // #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    // public function recordVisit(
    //     string $pageUrl,
    //     PageVisitRepository $repository,
    //     EntityManagerInterface $entityManager,
    //     LoggerInterface $logger
    // ): JsonResponse {
    //     // Log de la requête entrante
    //     $logger->info('Requête reçue pour enregistrer une visite.', ['pageUrl' => $pageUrl]);
    
    //     // Récupération et validation de l'origine
    //     $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    //     $allowedOrigins = ['https://aeonix-lake.vercel.app'];
    
    //     if (in_array($origin, $allowedOrigins)) {
    //         $headers = [
    //             'Access-Control-Allow-Origin' => $origin,
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //             'Access-Control-Allow-Credentials' => 'true',
    //         ];
    //         $logger->debug('Origine autorisée détectée.', ['origin' => $origin]);
    //     } else {
    //         $headers = [
    //             'Access-Control-Allow-Origin' => 'null',
    //         ];
    //         $logger->warning('Origine non autorisée.', ['origin' => $origin]);
    //     }
    
    //     // Gestion des requêtes OPTIONS
    //     if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    //         $logger->info('Requête OPTIONS détectée.');
    //         return new JsonResponse(null, Response::HTTP_NO_CONTENT, $headers);
    //     }
    
    //     try {
    //         // Normalisation de l'URL
    //         $pageUrl = rtrim(strtolower($pageUrl), '/');
    //         $logger->debug('URL normalisée.', ['pageUrl' => $pageUrl]);
    
    //         // Vérifier si la page existe déjà ou créer une nouvelle entrée
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount(); // Incrémentation du compteur
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         // Log de succès
    //         $logger->info('Visite enregistrée avec succès.', [
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ]);
    
    //         // Réponse JSON avec en-têtes CORS
    //         return new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ], JsonResponse::HTTP_OK, $headers);
    //     } catch (\Exception $e) {
    //         // Log de l'erreur
    //         $logger->error('Erreur lors de l\'enregistrement de la visite.', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);
    
    //         // Réponse d'erreur avec en-têtes CORS
    //         return new JsonResponse([
    //             'message' => 'Erreur lors de l\'enregistrement de la visite.',
    //             'error' => $e->getMessage(),
    //         ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $headers);
    //     }
    // }
    


    #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    public function recordVisit(
        string $pageUrl,
        PageVisitRepository $repository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): JsonResponse {
        // Lire les variables d'environnement
        $allowedOrigin = $_ENV['CORS_ALLOW_ORIGIN'] ?? '*';
        $allowedMethods = $_ENV['CORS_ALLOW_METHODS'] ?? 'GET, POST, OPTIONS';
        $allowedHeaders = $_ENV['CORS_ALLOW_HEADERS'] ?? 'Content-Type, Authorization';
        $allowCredentials = filter_var($_ENV['CORS_ALLOW_CREDENTIALS'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
    
        // Ajout des en-têtes CORS
        $headers = [
            'Access-Control-Allow-Origin' => $allowedOrigin,
            'Access-Control-Allow-Methods' => $allowedMethods,
            'Access-Control-Allow-Headers' => $allowedHeaders,
        ];
        if ($allowCredentials) {
            $headers['Access-Control-Allow-Credentials'] = 'true';
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            $logger->info('OPTIONS request handled for CORS preflight.');
            return new JsonResponse(null, Response::HTTP_NO_CONTENT, $headers);
        }
    
        try {
            // Logique métier
            $pageUrl = rtrim(strtolower($pageUrl), '/');
            $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
            $pageVisit->setPageUrl($pageUrl);
            $pageVisit->incrementVisitCount();
    
            $entityManager->persist($pageVisit);
            $entityManager->flush();
    
            $logger->info('Page visit successfully recorded.', ['pageUrl' => $pageUrl]);
            return new JsonResponse([
                'message' => 'Visite enregistrée avec succès.',
                'pageUrl' => $pageVisit->getPageUrl(),
                'visitCount' => $pageVisit->getVisitCount(),
            ], JsonResponse::HTTP_OK, $headers);
        } catch (\Exception $e) {
            $logger->error('Error recording page visit.', ['error' => $e->getMessage()]);
            return new JsonResponse([
                'message' => 'Erreur lors de l\'enregistrement de la visite.',
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $headers);
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