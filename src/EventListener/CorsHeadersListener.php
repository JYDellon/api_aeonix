<?php





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
$origin = $request->headers->get('Origin');
if ($origin === 'https://aeonix-lake.vercel.app') {
$response->headers->set('Access-Control-Allow-Origin', $origin);
}

$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
$response->headers->set('Access-Control-Allow-Credentials', 'false'); // Changez si nécessaire
}
}
}