<?php

use Symfony\Component\Dotenv\Dotenv;

// Charge les variables d'environnement
require dirname(__DIR__).'/vendor/autoload.php';
if (file_exists(dirname(__DIR__).'/.env')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}