<?php




//Middleware/CorsMiddleware.php
namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

// class CorsMiddleware implements EventSubscriberInterface
// {
//     // Fonction pour ajouter les en-têtes CORS
//     public function onKernelRequest(Request $request)
//     {
//         // Vérification si la méthode est OPTIONS pour gérer les pré-requêtes
//         if ($request->getMethod() === 'OPTIONS') {
//             return new Response('', Response::HTTP_OK, [
//                 'Access-Control-Allow-Origin' => '*',
//                 'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type',
//                 'Access-Control-Max-Age' => '3600',
//             ]);
//         }
//     }

//     // Enregistrer les événements du noyau
//     public static function getSubscribedEvents()
//     {
//         return [
//             KernelEvents::REQUEST => 'onKernelRequest',
//         ];
//     }
// }














namespace App\Middleware;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CorsMiddleware implements EventSubscriberInterface
{
    // Fonction pour ajouter les en-têtes CORS
    public function onKernelRequest(RequestEvent $event)
    {
        // Récupérer l'objet Request depuis l'événement
        $request = $event->getRequest();

        // Vérification si la méthode est OPTIONS pour gérer les pré-requêtes
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response('', Response::HTTP_OK, [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
            $event->setResponse($response);  // Définir la réponse pour l'événement
        }
    }

    // Définir les événements auxquels le middleware s'abonne
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }
}