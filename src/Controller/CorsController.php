<?php




namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class CorsController
{
public function preflight(): Response
{
$response = new Response();
$response->headers->set('Access-Control-Allow-Origin', 'https://aeonix-nine.vercel.app');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
$response->setStatusCode(204); // No Content
return $response;
}
}