<?php





namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CORSListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Intercepter les requêtes OPTIONS
        if ($request->getMethod() === 'OPTIONS') {
            $response = new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT, [
                'Access-Control-Allow-Origin' => $request->headers->get('Origin', '*'),
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'Access-Control-Allow-Credentials' => 'true',
            ]);
            $event->setResponse($response);
        }
    }
}