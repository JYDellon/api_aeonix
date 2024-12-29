<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Runtime\SymfonyRuntime;

require_once dirname(__DIR__).'/vendor/autoload.php';

return function (array $context) {
    $runtime = new SymfonyRuntime();

    // Résoudre le kernel à partir du contexte
    [$handler, $arguments] = $runtime->getResolver($context)->resolve();

    // Initialisation de la requête
    $request = Request::createFromGlobals();

    // Exécuter le gestionnaire et retourner la réponse
    $response = $handler(...$arguments)->handle($request);

    // Envoyer la réponse
    $response->send();

    return $response;
};