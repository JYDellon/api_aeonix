<?php




namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class TestController
{
public function index(): JsonResponse
{
return new JsonResponse(['message' => 'Symfony backend is working!']);
}
}