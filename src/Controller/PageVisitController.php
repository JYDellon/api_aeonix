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
    //     EntityManagerInterface $entityManager,
    //     Request $request
    // ): JsonResponse {
    //     // Gérer la requête OPTIONS
    //     if ($request->getMethod() === 'OPTIONS') {
    //         return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
    //             'Access-Control-Allow-Origin' => 'https://aeonix-blue.vercel.app',
    //             'Access-Control-Allow-Methods' => 'POST, OPTIONS',
    //             'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
    //         ]);
    //     }
    
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
    













    #[Route('/api/visit/{pageUrl}', name: 'api_record_visit', methods: ['POST', 'OPTIONS'])]
    public function recordVisit(
        string $pageUrl,
        PageVisitRepository $repository,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        // Gérer les requêtes OPTIONS (prévol)
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
                'Access-Control-Allow-Origin' => 'https://aeonix-blue.vercel.app',
                'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        }
        
    
        // Normalisation de l'URL
        $pageUrl = rtrim(strtolower($pageUrl), '/');
    
        try {
            // Vérifier si la page existe déjà
            $pageVisit = $repository->findOneBy(['pageUrl' => $pageUrl]) ?? new PageVisit();
            $pageVisit->setPageUrl($pageUrl);
            $pageVisit->incrementVisitCount(); // Incrémentation du compteur
    
            // Persister les données en base
            $entityManager->persist($pageVisit);
            $entityManager->flush();
    
            // Réponse avec succès
            return new JsonResponse([
                'message' => 'Visite enregistrée avec succès.',
                'pageUrl' => $pageVisit->getPageUrl(),
                'visitCount' => $pageVisit->getVisitCount(),
            ], JsonResponse::HTTP_OK, [
                'Access-Control-Allow-Origin' => 'https://aeonix-blue.vercel.app',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
        } catch (\Exception $e) {
            // Réponse en cas d'erreur
            return new JsonResponse([
                'message' => 'Erreur lors de l\'enregistrement de la visite.',
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR, [
                'Access-Control-Allow-Origin' => 'https://aeonix-blue.vercel.app',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
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