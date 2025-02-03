<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class StatusController extends AbstractController
{
    #[Route(path: '/status', name: 'app_status', methods: ['GET'])]
    public function status(): JsonResponse
    {
        return $this->json(['status' => 'ok']);
    }
}
