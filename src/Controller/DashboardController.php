<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function show(Request $request): Response
    {
        $cookieVal = $request->cookies->get('authToken');

        // On compare bêtement (pour l'exemple) la valeur attendue
        if ($cookieVal !== 'someRandomValue') {
            return new Response('Accès interdit : pas le bon cookie.', 403);
        }

        // Contenu protégé (ex. HTML minimal)
        $html = '<h1>Bienvenue dans le Dashboard sécurisé !</h1>';

        return new Response($html, 200);
    }
}