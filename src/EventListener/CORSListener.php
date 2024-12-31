<?php





// namespace App\EventListener;

// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpFoundation\JsonResponse;

// class CORSListener
// {
//     public function onKernelRequest(RequestEvent $event)
//     {
//         $request = $event->getRequest();

//         // Intercepter uniquement les requêtes OPTIONS
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
//                 'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
//                 'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
//                 'Access-Control-Allow-Credentials' => 'true',
//             ]);
//             $event->setResponse($response);
//         }
//     }
// }namespace App\EventListener;







// namespace App\EventListener;



// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpFoundation\JsonResponse;

// use Symfony\Component\HttpKernel\Event\ResponseEvent;

// class CORSListener
// {
//     public function onKernelRequest(RequestEvent $event)
//     {
//         $request = $event->getRequest();

//         // Réponse pour les requêtes OPTIONS
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
//                 'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
//                 'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
//                 'Access-Control-Allow-Credentials' => 'true',
//             ]);
//             $event->setResponse($response);
//         }
//     }

//     public function onKernelResponse(ResponseEvent $event)
//     {
//         $response = $event->getResponse();
//         $request = $event->getRequest();

//         // Ajouter les en-têtes CORS à toutes les réponses
//         $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', '*'));
//         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
//         $response->headers->set('Access-Control-Allow-Credentials', 'true');
//     }
// }namespace App\EventListener;







// namespace App\EventListener;



// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;
// use Symfony\Component\HttpFoundation\JsonResponse;

// class CORSListener
// {
//     public function onKernelRequest(RequestEvent $event)
//     {
//         $request = $event->getRequest();

//         // Gérer les requêtes OPTIONS
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
//                 'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
//                 'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
//                 'Access-Control-Allow-Credentials' => 'true',
//             ]);
//             $event->setResponse($response);
//         }
//     }

//     public function onKernelResponse(ResponseEvent $event)
//     {
//         $response = $event->getResponse();
//         $request = $event->getRequest();

//         // Ajouter les en-têtes CORS à toutes les réponses
//         $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', '*'));
//         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
//         $response->headers->set('Access-Control-Allow-Credentials', 'true');
//     }
// }namespace App\EventListener;










// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;
// use Symfony\Component\HttpFoundation\JsonResponse;






// class CORSListener
// {
//     public function onKernelRequest(RequestEvent $event)
//     {
//         $request = $event->getRequest();

//         // Réponse aux requêtes OPTIONS (pré-volantes)
//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
//                 'Access-Control-Allow-Origin' => $request->headers->get('Origin', 'https://apiaeonix-production.up.railway.app/'),
//                 'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
//                 'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
//                 'Access-Control-Allow-Credentials' => 'true',
//                 'Access-Control-Max-Age' => '3600',
//             ]);
//             $event->setResponse($response);
//         }
//     }

//     public function onKernelResponse(ResponseEvent $event)
//     {
//         $response = $event->getResponse();
//         $request = $event->getRequest();

//         // Ajouter les en-têtes CORS à toutes les réponses
//         $response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', 'https://apiaeonix-production.up.railway.app/'));
//         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
//         $response->headers->set('Access-Control-Allow-Credentials', 'true');
//     }
// }namespace App\EventListener;


















// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpKernel\Event\RequestEvent;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;

// class CorsListener
// {
//     public function onKernelRequest(RequestEvent $event): void
//     {
//         $request = $event->getRequest();

//         if ($request->getMethod() === 'OPTIONS') {
//             $response = new JsonResponse(null, 204);
//             $response->headers->set('Access-Control-Allow-Origin', '*');
//             $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
//             $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');

//             $event->setResponse($response);
//         }
//     }

//     public function onKernelResponse(ResponseEvent $event): void
//     {
//         $response = $event->getResponse();
//         $response->headers->set('Access-Control-Allow-Origin', '*');
//         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
//     }
// }<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsListener
{
    /**
     * Handle CORS for preflight (OPTIONS) requests
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Gérer uniquement les requêtes OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT); // 204 No Content
            $response->headers->set('Access-Control-Allow-Origin', '*'); // Changez "*" par l'URL de votre frontend si nécessaire
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Si vous utilisez des cookies ou des identifiants

            $event->setResponse($response);
        }
    }

    /**
     * Add CORS headers to all responses
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // Appliquer uniquement aux routes API
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            $response->headers->set('Access-Control-Allow-Origin', '*'); // Changez "*" par l'URL de votre frontend si nécessaire
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Si vous utilisez des cookies ou des identifiants
        }
    }
}