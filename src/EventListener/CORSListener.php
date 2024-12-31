<?php





namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CORSListener
{
public function onKernelRequest(RequestEvent $event)
{
$request = $event->getRequest();

// Gère les requêtes pré-volantes
if ($request->getMethod() === 'OPTIONS') {
$response = new JsonResponse(null, Response::HTTP_NO_CONTENT);
$response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', '*'));
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
$response->headers->set('Access-Control-Allow-Credentials', 'true');
$event->setResponse($response);
}
}

public function onKernelResponse(ResponseEvent $event)
{
$response = $event->getResponse();
$request = $event->getRequest();

// Ajout des en-têtes CORS pour toutes les réponses
$response->headers->set('Access-Control-Allow-Origin', $request->headers->get('Origin', '*'));
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
$response->headers->set('Access-Control-Allow-Credentials', 'true');
}
}