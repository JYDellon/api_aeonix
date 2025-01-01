<?php

// use App\Kernel;

// require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// return function (array $context) {
//     return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    
// };





use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // Récupérer les variables d'environnement via $_ENV
    $env = $_ENV['APP_ENV'] ?? 'prod'; // Si APP_ENV n'est pas défini, 'prod' est utilisé par défaut
    $debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN); // Convertir APP_DEBUG en booléen

    // Créez le Kernel Symfony avec ces variables d'environnement
    $kernel = new Kernel($env, $debug);
    return $kernel;
};