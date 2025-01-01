<?php

// namespace App\EventListener;

// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;

// class CorsListener
// {
//     /**
//      * Handle CORS for preflight (OPTIONS) requests
//      */
//     public function onKernelRequest(RequestEvent $event): void
//     {
//         $request = $event->getRequest();

//         // Gérer uniquement les requêtes OPTIONS
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT); // 204 No Content
//             $response->headers->set('Access-Control-Allow-Origin', '*'); // Changez "*" par l'URL de votre frontend si nécessaire
//             $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//             $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
//             $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Si vous utilisez des cookies ou des identifiants

//             $event->setResponse($response);
//         }
//     }

//     /**
//      * Add CORS headers to all responses
//      */
//     public function onKernelResponse(ResponseEvent $event): void
//     {
//         $response = $event->getResponse();
//         $request = $event->getRequest();

//         // Appliquer uniquement aux routes API
//         if (strpos($request->getPathInfo(), '/api/') === 0) {
//             $response->headers->set('Access-Control-Allow-Origin', '*'); // Changez "*" par l'URL de votre frontend si nécessaire
//             $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//             $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
//             $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Si vous utilisez des cookies ou des identifiants
//         }
//     }
// }








namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getMethod() === 'OPTIONS') {
            $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
            $this->addCorsHeaders($response, $request);
            $event->setResponse($response);
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if (strpos($request->getPathInfo(), '/api/') === 0) {
            $this->addCorsHeaders($response, $request);
        }
    }

    private function addCorsHeaders(JsonResponse $response, $request): void
    {
        $response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-lake.vercel.app');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
    }
}