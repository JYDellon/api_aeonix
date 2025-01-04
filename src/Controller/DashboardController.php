<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/api/dashboard', name: 'app_dashboard')]
    public function index(Request $request): Response
    {
        // Récupérer la valeur du cookie
        $cookieValue = $request->cookies->get('myAccessCookie');

        // Vérifier la valeur
        if ($cookieValue !== 'secretValue') {
            // Soit vous retournez un 403
            return new Response('Forbidden', 403);

            // Ou alors vous faites une redirection
            // return $this->redirect('/'); 
        }

        // Sinon on continue. L'utilisateur peut voir le dashboard
        // ... votre logique d'affichage ou renvoyer des données JSON
        return new Response('Bienvenue sur le Dashboard (Accès autorisé)');
    }
}