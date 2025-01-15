<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionTestController extends AbstractController
{
    public function index(Request $request, SessionInterface $session): Response
    {
        // Ajouter des données dans la session
        $session->set('test_key', 'test_value');

        // Lire les données depuis la session
        $testValue = $session->get('test_key', 'default_value');

        return new Response(sprintf('Session value for "test_key": %s', $testValue));
    }
}