<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class HomeController
{
    // #[Route('/', name: 'homepage', methods: ['GET'])]
    // public function index(): Response
    // {
    //     return new Response('<h1>Projet Symfony OK!</h1>');
    // }


    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}