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
        // On récupère ?secret=... dans l'URL
        $secret = $request->query->get('secret');
        // À adapter : tu peux générer un vrai mot de passe, ou un token plus complexe
        $mySecretKey = 'MON_CODE_PERSO';

        // Si le secret fourni est faux ou absent → 401
        if ($secret !== $mySecretKey) {
            return new JsonResponse(
                ['error' => 'Unauthorized. Wrong secret key.'], 
                Response::HTTP_UNAUTHORIZED
            );
        }

        // Si c'est le bon secret, on crée la réponse OK
        $response = new JsonResponse([
            'success' => true,
            'message' => 'Cookie défini avec succès !'
        ]);

        // Vérifions si on a déjà un cookie 'authToken'
        $alreadyHasCookie = $request->cookies->get('authToken');
        if (!$alreadyHasCookie) {
            // Crée un cookie 'authToken' (valeur statique pour l'exemple)
            $cookie = Cookie::create('authToken')
                ->withValue('someRandomValue')        // tu peux générer un token aléatoire
                ->withExpires(strtotime('+1 year'))   // expire dans 1 an
                ->withPath('/')
                ->withDomain('localhost')             // adapter pour ton domaine
                ->withSecure(false)                   // true si HTTPS
                ->withHttpOnly(true);                 // empêche l'accès JS direct

            // On l'attache à la réponse
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}