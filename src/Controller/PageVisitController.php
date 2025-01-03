<?php

namespace App\Controller;

use App\Entity\PageVisit;
use App\Repository\PageVisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    //             'Access-Control-Allow-Origin' => 'https://apiaeonix-production.up.railway.app*', // Remplace "*" par une origine spécifique si nécessaire
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
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
    //             'Access-Control-Allow-Origin' => 'https://apiaeonix-production.up.railway.app', 
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //         ]);
    //     } catch (\Exception $e) {
    //         // Réponse d'erreur avec en-têtes CORS
    //         return new JsonResponse([
    //             'message' => 'Erreur lors de l\'enregistrement de la visite.',
    //             'error' => $e->getMessage(),
    //         ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, [
    //             'Access-Control-Allow-Origin' => 'https://apiaeonix-production.up.railway.app',
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //         ]);
    //     }
    // }
    






    








    // #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    // public function recordVisit(
    //     string $pageUrl,
    //     PageVisitRepository $repository,
    //     EntityManagerInterface $entityManager
    // ): JsonResponse {
    //     $pageUrl = rtrim(strtolower($pageUrl), '/');
    
    //     try {
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount();
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         return new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ], JsonResponse::HTTP_OK);
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
    //     EntityManagerInterface $entityManager,
    //     Request $request
    // ): JsonResponse {
    //     if ($request->getMethod() === 'OPTIONS') {
    //         // Gérer la pré-requête OPTIONS
    //         $response = new JsonResponse(null, JsonResponse::HTTP_OK);
    //         // Ajouter les en-têtes CORS pour OPTIONS
    //         $response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-lake.vercel.app');
    //         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    //         $response->headers->set('Access-Control-Max-Age', '3600');
    //         return $response;
    //     }
    
    //     // Gestion des requêtes POST
    //     $pageUrl = rtrim(strtolower($pageUrl), '/');
    
    //     try {
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount();
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         return new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ], JsonResponse::HTTP_OK);
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
    //     EntityManagerInterface $entityManager,
    //     Request $request
    // ): JsonResponse {
    //     // Gérer la pré-requête OPTIONS
    //     if ($request->getMethod() === 'OPTIONS') {
    //         $response = new Response();
    
    
    
    
    //         // Ajouter les en-têtes CORS pour OPTIONS
    //         $response->headers->set('Access-Control-Allow-Origin', '*'); // Utiliser '*' pour autoriser toutes les origines
    //         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    //         $response->headers->set('Access-Control-Max-Age', '3600');
    
    //         // Retourner la réponse sans corps pour les pré-requêtes OPTIONS
    //         return $response;
    //     }
    
    //     // Gestion des requêtes POST
    //     $pageUrl = rtrim(strtolower($pageUrl), '/');
    
    //     try {
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount();
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         return new JsonResponse([
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ], JsonResponse::HTTP_OK);
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
    //     EntityManagerInterface $entityManager,
    //     Request $request
    // ): Response {
    //     // Vérifier si c'est une requête OPTIONS (pré-requête CORS)
    //     if ($request->getMethod() === 'OPTIONS') {
    //         return $this->handleOptionsRequest();
    //     }
    
    //     // Enregistrer la visite pour la page
    //     $pageUrl = rtrim(strtolower($pageUrl), '/');
    //     try {
    //         $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
    //         $pageVisit->setPageUrl($pageUrl);
    //         $pageVisit->incrementVisitCount(); // Incrémentation du compteur
    
    //         $entityManager->persist($pageVisit);
    //         $entityManager->flush();
    
    //         $responseContent = [
    //             'message' => 'Visite enregistrée avec succès.',
    //             'pageUrl' => $pageVisit->getPageUrl(),
    //             'visitCount' => $pageVisit->getVisitCount(),
    //         ];
    
    //         // Vérifier si c'est une requête JSONP en vérifiant le paramètre 'callback'
    //         $callback = $request->query->get('callback');  // Récupérer le paramètre 'callback' de la requête
    
    //         if ($callback) {
    //             // Si c'est une requête JSONP, renvoyer la réponse dans le format JSONP
    //             $content = $callback . '(' . json_encode($responseContent) . ');';
    //             return new Response($content, 200, ['Content-Type' => 'application/javascript']);
    //         }
    
    //         // Si ce n'est pas une requête JSONP, retourner la réponse classique en JSON
    //         return new JsonResponse($responseContent, JsonResponse::HTTP_OK);
    //     } catch (\Exception $e) {
    //         // Gestion des erreurs
    //         $errorResponse = [
    //             'message' => 'Erreur lors de l\'enregistrement de la visite.',
    //             'error' => $e->getMessage(),
    //         ];
    
    //         // Si c'est une requête JSONP en cas d'erreur
    //         $callback = $request->query->get('callback');  // Vérifier le paramètre 'callback' pour JSONP
    
    //         if ($callback) {
    //             // Retourner l'erreur en format JSONP
    //             $content = $callback . '(' . json_encode($errorResponse) . ');';
    //             return new Response($content, 200, ['Content-Type' => 'application/javascript']);
    //         }
    
    //         // Retourner l'erreur en format classique JSON
    //         return new JsonResponse($errorResponse, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
    
    // // Fonction pour gérer la pré-requête OPTIONS (CORS)
    // private function handleOptionsRequest(): Response
    // {
    //     // Réponse CORS pour les pré-requêtes OPTIONS
    //     $response = new Response();
    //     $response->headers->set('Access-Control-Allow-Origin', '*'); // Remplacer '*' par l'origine souhaitée si nécessaire
    //     $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //     $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    //     $response->headers->set('Access-Control-Max-Age', '3600'); // Durée de mise en cache des pré-requêtes

    //     // Retourner la réponse sans corps pour les pré-requêtes OPTIONS
    //     return $response;
    // }







    #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    public function recordVisit(
        string $pageUrl,
        PageVisitRepository $repository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        // Gérer les requêtes OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            return $this->handleOptionsRequest();
        }
    
        // Traiter les visites
        $pageUrl = rtrim(strtolower($pageUrl), '/');
        try {
            $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
            $pageVisit->setPageUrl($pageUrl);
            $pageVisit->incrementVisitCount();
            $entityManager->persist($pageVisit);
            $entityManager->flush();
    
            $responseContent = [
                'message' => 'Visite enregistrée avec succès.',
                'pageUrl' => $pageVisit->getPageUrl(),
                'visitCount' => $pageVisit->getVisitCount(),
            ];
    
            // Vérifier si c'est une requête JSONP
            $callback = $request->query->get('callback');
            if ($callback) {
                // Réponse JSONP
                $content = $callback . '(' . json_encode($responseContent) . ');';
                return new Response($content, 200, ['Content-Type' => 'application/javascript']);
            }
    
            // Retourner en JSON si ce n'est pas une requête JSONP
            return new JsonResponse($responseContent, JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            // Gestion des erreurs
            $errorResponse = [
                'message' => 'Erreur lors de l\'enregistrement de la visite.',
                'error' => $e->getMessage(),
            ];
    
            // Vérification JSONP en cas d'erreur
            $callback = $request->query->get('callback');
            if ($callback) {
                $content = $callback . '(' . json_encode($errorResponse) . ');';
                return new Response($content, 200, ['Content-Type' => 'application/javascript']);
            }
    
            return new JsonResponse($errorResponse, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

// Fonction pour gérer la pré-requête OPTIONS (CORS)
// Cette fonction gère la pré-requête OPTIONS pour CORS
// private function handleOptionsRequest(): Response
// {
//     // Réponse CORS pour les pré-requêtes OPTIONS
//     $response = new Response();
//     $response->headers->set('Access-Control-Allow-Origin', '*'); // Permet toutes les origines
//     $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//     $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
//     $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Permet l'envoi de cookies si nécessaire
//     $response->headers->set('Access-Control-Max-Age', '3600'); // Durée de mise en cache des pré-requêtes

//     // Retourner la réponse sans corps pour les pré-requêtes OPTIONS
//     return $response;
// }








private function handleOptionsRequest(): Response
{
    // Réponse CORS pour les pré-requêtes OPTIONS
    $response = new Response();
    $response->headers->set('Access-Control-Allow-Origin', '*'); // Remplacer '*' par l'origine souhaitée si nécessaire
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Ajout pour permettre les cookies si nécessaire
    $response->headers->set('Access-Control-Max-Age', '3600'); // Durée de mise en cache des pré-requêtes

    // Retourner la réponse sans corps pour les pré-requêtes OPTIONS
    return $response;
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