<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(): Response
    {
        // Redirige l'utilisateur vers la page de connexion
        return $this->redirectToRoute('login');
    }

    // #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     // Récupérer l'erreur d'authentification s'il y en a
    //     $error = $authenticationUtils->getLastAuthenticationError();

    //     // Dernier nom d'utilisateur saisi
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     // Rendu de la page de connexion
    //     return $this->render('security/login.html.twig', [
    //         'last_username' => $lastUsername,
    //         'error' => $error,
    //     ]);
    // }

    #[Route('/admin', name: 'admin', methods: ['GET'])]
    public function admin(): Response
    {
        // Cette vérification garantit que seuls les utilisateurs authentifiés avec ROLE_ADMIN accèdent à cette route.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        // Vérifie si l'utilisateur est déjà authentifié
        if ($this->getUser()) {
            return $this->redirectToRoute('admin'); // Redirige vers l'admin si déjà connecté
        }
    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    

















    #[Route('/check-auth', name: 'check_auth')]
public function checkAuth(): Response
{
    if ($this->isGranted('ROLE_ADMIN')) {
        return new Response('Authentifié avec succès !');
    }

    return new Response('Non authentifié.');
}













}