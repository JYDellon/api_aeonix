<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CookieController extends AbstractController
{
    #[Route('/api/set-cookie', name: 'app_set_cookie')]
    public function setCookie(): Response
    {
        $response = new Response('Cookie set');
        
        // Exemple : on crée un cookie "myAccessCookie" avec une valeur "secretValue"
        // Ajustez la valeur, le nom, l'expiration, etc. selon vos besoins
        $cookie = Cookie::create('myAccessCookie')
            ->withValue('secretValue')
            ->withExpires(strtotime('+1 year'))  // expire dans un an
            ->withPath('/')                      // accessible sur tout le site
            // ->withDomain('votre-domaine.fr')   // à adapter si besoin
            ->withSecure(false)                  // si vous êtes en HTTPS, mettez true
            ->withHttpOnly(true);                // évite qu’un script JS y accède
                                                // (à adapter selon vos besoins)
        
        // On attache le cookie à la réponse HTTP
        $response->headers->setCookie($cookie);

        return $response;
    }
}