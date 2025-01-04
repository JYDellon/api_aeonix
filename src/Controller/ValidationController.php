<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidationController extends AbstractController
{
    public function validateAccess(Request $request): Response
    {
        $authToken = $request->cookies->get('authToken');
        $allowedUUID = '123e4567-e89b-12d3-a456-426614174000';

        if ($authToken !== $allowedUUID) {
            return $this->json(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        return $this->json(['success' => true, 'message' => 'Access granted'], Response::HTTP_OK);
    }
}