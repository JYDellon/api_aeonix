<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

// Adaptation pour serverless
return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

    // Créer une requête HTTP à partir des variables globales
    $request = Request::createFromGlobals();

    // Gérer la requête
    $response = $kernel->handle($request);

    // Ajouter les en-têtes CORS (si nécessaire)
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

    // Envoyer la réponse
    $response->send();

    $kernel->terminate($request, $response);
};