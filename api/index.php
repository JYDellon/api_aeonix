<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

// Serverless adaptation
return function (array $context) {
    $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);

    // Create Symfony request object
    $request = Request::createFromGlobals();

    // Handle request
    $response = $kernel->handle($request);

    // Add CORS headers if needed
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    
    // Send response
    $response->send();

    $kernel->terminate($request, $response);
};