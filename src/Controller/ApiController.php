<?php








// src/Controller/ApiController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiController
{
/**
* @Route("/api/{path}", methods={"OPTIONS"}, name="api_options")
*/
public function options(Request $request): Response
{
// Répondre avec les bons en-têtes CORS pour la pré-requête OPTIONS
$response = new Response();
$response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-lake.vercel.app');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
$response->headers->set('Access-Control-Max-Age', '3600');

return $response;
}
}