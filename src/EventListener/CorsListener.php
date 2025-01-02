<?php



namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Psr\Log\LoggerInterface;

class CorsListener
{
    private $logger;

    // Injecter le logger pour ajouter des logs de débogage
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    // Traiter la requête pour OPTIONS (pré-requête CORS)
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Si c'est une pré-requête OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            $this->logger->info('CORS preflight request received for ' . $request->getPathInfo());

            $response = new Response();
            $this->addCorsHeaders($response, $request);  // Ajouter les en-têtes CORS
            $event->setResponse($response);  // Définir la réponse pour la pré-requête

            $this->logger->info('CORS headers set for OPTIONS request');
        }
    }

    // Appliquer les en-têtes CORS aux réponses (GET, POST, PUT, DELETE, OPTIONS)
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $this->addCorsHeaders($response, $event->getRequest());

        $this->logger->info('CORS headers applied to response');
    }

    // Ajout des en-têtes CORS dans la réponse
    private function addCorsHeaders(Response $response, $request): void
    {
        // Applique les en-têtes CORS uniquement si la requête est sur /api/
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            // Récupère l'origine de la requête
            $origin = $request->headers->get('Origin');
            
            // Si l'origine est vide, on permet toutes les origines (ou vous pouvez personnaliser cette logique)
            if (!$origin) {
                $origin = '*';  // Permet toutes les origines
            }
    
            // Ajouter l'en-tête Access-Control-Allow-Origin avec la valeur de l'origine de la requête
            $response->headers->set('Access-Control-Allow-Origin', $origin);
    
            // Autres en-têtes nécessaires pour CORS
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
            $response->headers->set('Access-Control-Max-Age', '3600'); // Durée de mise en cache des pré-requêtes
    
            // Log pour vérifier que les en-têtes ont bien été ajoutés
            $this->logger->info('CORS header set for origin: ' . $origin);
        }
    }
    
}