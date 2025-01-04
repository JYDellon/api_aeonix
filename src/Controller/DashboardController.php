<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;

// class DashboardController extends AbstractController
// {
//     public function show(Request $request): Response
//     {
//         // Récupère le cookie 'authToken'
//         $cookieVal = $request->cookies->get('authToken');

//         // Vérifie si la valeur du cookie est celle attendue
//         if ($cookieVal !== 'someRandomValue') {
//             // Si le cookie est invalide ou absent, retourne un code 403
//             return new Response('Accès interdit : pas le bon cookie.', Response::HTTP_FORBIDDEN);
//         }

//         // Si l'accès est autorisé, retourne le contenu sécurisé
//         $html = '<h1>Bienvenue dans le Dashboard sécurisé !</h1>
//                  <p>Ceci est une zone protégée accessible uniquement avec le bon cookie.</p>';

//         return new Response($html, Response::HTTP_OK);
//     }
// }











namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    public function dashboard(Request $request): JsonResponse
    {
        // Liste blanche des UUID autorisés
        $allowedUUIDs = [
            'ton-uuid-unique-genere-pour-ce-pc',
        ];

        // Récupère le UUID de l'en-tête
        $deviceUUID = $request->headers->get('X-Device-ID');

        // Vérifie si le UUID est autorisé
        if (!in_array($deviceUUID, $allowedUUIDs, true)) {
            return new JsonResponse(['error' => 'Accès non autorisé.'], 403);
        }

        // Retourne une réponse si autorisé
        return new JsonResponse(['message' => 'Bienvenue dans le tableau de bord sécurisé.'], 200);
    }
}