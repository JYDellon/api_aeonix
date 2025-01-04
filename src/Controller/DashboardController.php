<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function show(Request $request): Response
    {
        // Récupération du cookie 'authToken'
        $cookieUUID = $request->cookies->get('authToken');

        // Liste blanche des UUID autorisés
        $allowedUUIDs = [
            '123e4567-e89b-12d3-a456-426614174000', // UUID autorisé
        ];

        // Vérification de l'accès
        if (!in_array($cookieUUID, $allowedUUIDs, true)) {
            return new Response('Accès interdit : UUID non autorisé.', Response::HTTP_FORBIDDEN);
        }

        // Contenu protégé
        return new Response('<h1>Bienvenue dans le Dashboard sécurisé !</h1>', Response::HTTP_OK);
    }
}