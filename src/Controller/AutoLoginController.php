<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginController extends AbstractController
{
    public function autoLogin(Request $request): Response
    {
        // Récupère le paramètre ?secret= dans l'URL
        $secret = $request->query->get('secret');
        $mySecretKey = 'MON_CODE_PERSO'; // Secret clé attendu

        // Si le secret est invalide, retourne un code 401 (Unauthorized)
        if ($secret !== $mySecretKey) {
            return new JsonResponse(
                ['error' => 'Unauthorized. Wrong secret key.'], 
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Crée une réponse JSON pour signaler que le cookie a été défini
        $response = new JsonResponse([
            'success' => true,
            'message' => 'Cookie défini avec succès !'
        ]);

        // Vérifie si le cookie 'authToken' existe déjà
        $alreadyHasCookie = $request->cookies->get('authToken');
        if (!$alreadyHasCookie) {
            // Crée un cookie avec les attributs nécessaires
            $cookie = Cookie::create('authToken')
                ->withValue('someRandomValue')        // Valeur du cookie (à rendre plus complexe en production)
                ->withExpires(strtotime('+1 year'))   // Durée de vie : 1 an
                ->withPath('/')                       // Valide pour toutes les routes
                ->withDomain('localhost')             // Remplace par ton domaine
                ->withSecure(false)                   // Mettre à true si HTTPS
                ->withHttpOnly(true);                 // Protège contre l'accès JavaScript

            // Attache le cookie à la réponse
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}