<?php 


// src/Controller/IpVerificationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IpVerificationController extends AbstractController
{
    /**
     * @Route("/check-ip", name="check_ip")
     */
    public function checkIp(): JsonResponse
    {
        // Ton adresse IP statique (celle de ton PC personnel, par exemple)
        $staticIp = '192.168.1.100';  // Remplace avec ton IP statique

        // Récupérer l'IP réelle de l'utilisateur
        $userIp = $this->getUserIp();

        // Comparer les IPs
        if ($userIp === $staticIp) {
            return new JsonResponse(['isAuthorized' => true]);
        } else {
            return new JsonResponse(['isAuthorized' => false]);
        }
    }

    private function getUserIp(): string
    {
        // Vérifier si l'IP est transmise via un proxy (X-Forwarded-For)
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // L'IP réelle se trouve dans le premier champ de cette liste (parfois, plusieurs IP peuvent être présentes)
            return strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
        }

        // Si l'IP n'est pas derrière un proxy, utiliser l'IP directe
        return $_SERVER['REMOTE_ADDR'];
    }
}