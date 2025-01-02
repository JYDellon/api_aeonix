<?php




//Middleware/CorsMiddleware.php
namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CorsMiddleware implements EventSubscriberInterface
{
    // Fonction pour ajouter les en-têtes CORS
    public function onKernelRequest(Request $request)
    {
        // Vérification si la méthode est OPTIONS pour gérer les pré-requêtes
        if ($request->getMethod() === 'OPTIONS') {
            return new Response('', Response::HTTP_OK, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type',
                'Access-Control-Max-Age' => '3600',
            ]);
        }
    }

    // Enregistrer les événements du noyau
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}