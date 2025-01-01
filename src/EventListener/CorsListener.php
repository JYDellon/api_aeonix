<?php


// namespace App\EventListener;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;

// class CorsListener
// {
//     public function onKernelRequest(RequestEvent $event): void
//     {
//         $request = $event->getRequest();

//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new Response();
//             $this->addCorsHeaders($response);
//             $event->setResponse($response);
//         }
//     }

//     public function onKernelResponse(ResponseEvent $event): void
//     {
//         $response = $event->getResponse();
//         $this->addCorsHeaders($response);
//     }

//     private function addCorsHeaders(Response $response): void
//     {
//         $response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-lake.vercel.app'); // Définir votre URL frontend ici
//         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
//         $response->headers->set('Access-Control-Allow-Credentials', 'true');
//     }
// }











namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Vérifie si c'est une pré-requête OPTIONS (pour gérer les requêtes CORS)
        if ($request->getMethod() === 'OPTIONS') {
            $response = new Response();
            $this->addCorsHeaders($response, $request);
            $event->setResponse($response);
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $this->addCorsHeaders($response, $event->getRequest());
    }

    private function addCorsHeaders(Response $response, $request): void
    {
        // Applique les en-têtes CORS uniquement si la requête est sur /api/
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            $origin = $request->headers->get('Origin');
            
            // Permet uniquement une origine spécifique, vous pouvez ajouter plus de logique ici si nécessaire
            if ($origin === 'https://aeonix-lake.vercel.app') {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
            }
            
            // Autres en-têtes nécessaires pour CORS
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '3600');
        }
    }
}