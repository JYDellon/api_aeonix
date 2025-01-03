<?php 






// src/Controller/AuthController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login()
    {
        // Crée un token pour l'utilisateur
        $token = 'e82de727-19e3-47f7-bd3f-42d7ab54120c';  // Utiliser un token sécurisé comme un JWT pour la production

        // Crée une réponse et ajoute le cookie HTTPOnly
        $response = new Response('Login successful');
        
        // Crée un cookie 'access_token' avec une durée de vie de 1 heure (3600 secondes)
        $response->headers->setCookie(
            new Cookie('access_token', $token, time() + 3600, '/', null, true, true)
        );
        
        // Renvoie la réponse avec le cookie
        return $response;
    }
}