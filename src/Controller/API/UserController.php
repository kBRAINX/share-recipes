<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/api/me', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function me():JsonResponse
    {
        return $this->json($this->getUser());
    }
}
