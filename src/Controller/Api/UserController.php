<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\Mapper\User\UserMapper;
use App\Dto\Request\RegisterRequestDto;
use App\Service\UserService;
use Random\RandomException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api', name: 'route_api_')]
final class UserController extends ApiController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly UserMapper $userMapper,
    ) {
    }

    /**
     * @throws RandomException
     */
    #[Route(path: '/register', name: 'register', methods: [Request::METHOD_POST])]
    public function register(RegisterRequestDto $dto): JsonResponse
    {
        $this->userService->createUser($dto);

        return $this->noContent();
    }

    #[Route(path: '/user', name: 'user_read', methods: [Request::METHOD_GET])]
    public function user(): JsonResponse
    {
        return $this->json(
            $this->userMapper->mapDto($this->userService->getAuthorizedUser()),
        );
    }
}
