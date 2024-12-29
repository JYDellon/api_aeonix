<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Crée une requête Symfony à partir des variables globales
$request = Request::createFromGlobals();

// Initialise le Kernel Symfony
$kernel = new Kernel('prod', (bool) ($_SERVER['APP_DEBUG'] ?? false));

// Gère la requête et renvoie la réponse
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);