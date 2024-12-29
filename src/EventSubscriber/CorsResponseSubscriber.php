<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsResponseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // Ajoute les en-têtes CORS si la requête correspond à une route spécifique
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            $response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-blue.vercel.app');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, DELETE, PUT');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }
    }
}