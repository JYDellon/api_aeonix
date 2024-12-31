<?php






namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // Ajoutez les en-têtes uniquement pour les endpoints commençant par /api/
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            $origin = $request->headers->get('Origin');
            if ($origin === 'https://aeonix-lake.vercel.app') {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            }
        }
    }
}