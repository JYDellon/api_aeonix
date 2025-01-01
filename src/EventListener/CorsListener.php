<?php






namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Psr\Log\LoggerInterface;

class CorsListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Vérifie si c'est une pré-requête OPTIONS (pour gérer les requêtes CORS)
        if ($request->getMethod() === 'OPTIONS') {
            $this->logger->info('CORS preflight request received for ' . $request->getPathInfo());

            $response = new Response();
            $this->addCorsHeaders($response, $request);
            $event->setResponse($response);

            $this->logger->info('CORS headers set for OPTIONS request');
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $this->addCorsHeaders($response, $event->getRequest());

        $this->logger->info('CORS headers applied to response');
    }

    // private function addCorsHeaders(Response $response, $request): void
    // {
    //     // Applique les en-têtes CORS uniquement si la requête est sur /api/
    //     if (strpos($request->getPathInfo(), '/api/') === 0) {
    //         $origin = $request->headers->get('Origin');
            
    //         // Permet uniquement une origine spécifique
    //         if ($origin === 'https://aeonix-lake.vercel.app') {
    //             $response->headers->set('Access-Control-Allow-Origin', $origin);
    //             $this->logger->info('CORS header set for origin: ' . $origin);
    //         } else {
    //             $this->logger->warning('Origin not allowed: ' . $origin);
    //         }

    //         // Autres en-têtes nécessaires pour CORS
    //         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
    //         $response->headers->set('Access-Control-Allow-Credentials', 'true');
    //         $response->headers->set('Access-Control-Max-Age', '3600');
    //     }
    // }


    private function addCorsHeaders(Response $response, $request): void
    {
        // Applique les en-têtes CORS uniquement si la requête est sur /api/
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            // Autoriser toutes les origines (ou une autre logique selon ce que vous préférez)
            $origin = $request->headers->get('Origin');
            
            // Ajouter l'en-tête Access-Control-Allow-Origin avec la valeur de l'origin de la requête
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            
            // Autres en-têtes nécessaires pour CORS
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '3600');
            
            // Log
            $this->logger->info('CORS header set for origin: ' . $origin);
        }
    }
    

    
}