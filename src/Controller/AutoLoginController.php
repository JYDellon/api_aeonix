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
        // Récupérer le UUID du frontend
        $uuid = $request->query->get('uuid');
        $allowedUUID = '123e4567-e89b-12d3-a456-426614174000'; // UUID autorisé

        if ($uuid !== $allowedUUID) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Définir un cookie avec l'UUID comme valeur
        $cookie = Cookie::create('authToken')
            ->withValue($uuid)                  // Stocke le UUID
            ->withExpires(strtotime('+1 year')) // Expire dans un an
            ->withPath('/')
            ->withSecure(false)                 // HTTPS si possible
            ->withHttpOnly(true);               // Inaccessible par JavaScript

        // Réponse avec le cookie
        $response = new JsonResponse(['success' => true, 'message' => 'Cookie défini avec succès']);
        $response->headers->setCookie($cookie);

        return $response;
    }
}