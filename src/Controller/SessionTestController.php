<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionTestController extends AbstractController
{
    // public function testSession(Request $request, SessionInterface $session): Response
    // {
    //     $counter = $session->get('counter', 0);
    //     $counter++;
    //     $session->set('counter', $counter);

    //     return new Response("Counter value: $counter");
    // }


    public function testSession(Request $request): Response
    {
        $session = $request->getSession();
        $counter = $session->get('counter', 0);
        $counter++;
        $session->set('counter', $counter);

        return new Response("Session counter value: $counter");
    }


    
}