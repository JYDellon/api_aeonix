<?php





// namespace App\EventListener;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Event\ResponseEvent;

// class CorsHeadersListener
// {
// public function onKernelResponse(ResponseEvent $event): void
// {
// $request = $event->getRequest();
// $response = $event->getResponse();

// // Appliquez uniquement aux routes /api/
// if (strpos($request->getPathInfo(), '/api/') === 0) {
// $origin = $request->headers->get('Origin');
// if ($origin === 'https://aeonix-lake.vercel.app') {
// $response->headers->set('Access-Control-Allow-Origin', $origin);
// }

// $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
// $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
// $response->headers->set('Access-Control-Allow-Credentials', 'false'); // Changez si nécessaire
// }
// }
// }











namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsHeadersListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        // Appliquez uniquement aux routes /api/
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            // Récupère l'origine de la requête
            $origin = $request->headers->get('Origin');
            
            // Si l'origine est vide, permet toutes les origines
            if (!$origin) {
                $origin = '*';  // Permet toutes les origines
            }

            // Ajouter l'en-tête Access-Control-Allow-Origin avec la valeur de l'origine de la requête
            $response->headers->set('Access-Control-Allow-Origin', $origin);

            // Autres en-têtes nécessaires pour CORS
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
            // $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Max-Age', '3600');

            // Log pour vérifier que les en-têtes ont bien été ajoutés
            // Vous pouvez commenter cette ligne en production si vous n'avez pas besoin de logs
            // $this->logger->info('CORS header set for origin: ' . $origin);
        }
    }
}