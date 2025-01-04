<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route; <-- On peut le supprimer si on n'utilise pas l'annotation

class AutoLoginController extends AbstractController
{
    // Plus d’annotation ici
    public function autoLogin(Request $request): Response
    {
        // On vérifie si on a déjà un cookie nommé 'authToken'
        $alreadyHasCookie = $request->cookies->get('authToken');

        // Construire la réponse
        $response = new Response();
        $response->setStatusCode(Response::HTTP_OK);

        // Si le cookie 'authToken' n'existe pas, on le crée
        if (!$alreadyHasCookie) {
            $cookie = Cookie::create('authToken')
                ->withValue('someRandomValue')
                ->withExpires(strtotime('+1 year'))
                ->withPath('/')
                ->withDomain('localhost')
                ->withSecure(false)    // true si HTTPS
                ->withHttpOnly(true);

            $response->headers->setCookie($cookie);
        }

        // JSON minimal pour dire "ok"
        $response->setContent(json_encode([
            'success' => true,
            'message' => 'Cookie défini ou déjà présent',
        ]));

        return $response;
    }
}