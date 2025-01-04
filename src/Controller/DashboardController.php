<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    #[Route('/api/dashboard', name: 'dashboard', methods: ['GET'])]
    public function getDashboardData(Request $request): JsonResponse
    {
        // Récupère le cookie 'access_token' envoyé avec la requête
        $token = $request->cookies->get('access_token');
        $secretToken = 'e82de727-19e3-47f7-bd3f-42d7ab54120c';  // Ton token secret

        // Vérifie si le token correspond à celui attendu
        if ($token === $secretToken) {
            // Création de la réponse avec les données du tableau de bord
            $response = new JsonResponse(['message' => 'Welcome to the dashboard']);

            // Ajout des en-têtes CORS
            $response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-lake.vercel.app');  // Origine autorisée
            $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Permet l'envoi des cookies
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');  // Méthodes autorisées
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');  // En-têtes autorisés

            return $response;  // Retourne la réponse
        } else {
            return new JsonResponse(['message' => 'Access Denied'], JsonResponse::HTTP_FORBIDDEN);  // Accès refusé si le token est invalide
        }
    }
}