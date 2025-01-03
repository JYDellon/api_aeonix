<?php 





// src/Controller/IpController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IpController extends AbstractController
{
    #[Route('/api/get-ip', name: 'get_ip', methods: ['GET'])]
    public function getIp(): JsonResponse
    {
        // Récupérer l'IP du client depuis la requête
        // $clientIp = $_SERVER['REMOTE_ADDR'];
        $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];


        // Si vous voulez récupérer l'IP depuis un proxy (par exemple si vous êtes derrière un reverse proxy comme Nginx ou Apache)
        // $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

        return new JsonResponse(['ip' => $clientIp]);
    }
}