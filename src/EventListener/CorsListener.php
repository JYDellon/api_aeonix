<?php



namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CorsListener
{
public function onKernelResponse(ResponseEvent $event)
{
$response = $event->getResponse();

$response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-nine.vercel.app');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
}
}