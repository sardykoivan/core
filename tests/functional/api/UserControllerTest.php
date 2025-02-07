<?php

declare(strict_types=1);

namespace App\Tests\functional\api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

final class UserControllerTest extends ApiTestCase
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getContainer()->get(UserRepository::class);
    }

    public function testRegistrationSuccess(): void
    {
        $this->client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/register',
            parameters: [
                'email' => 'registered@test.com',
                'password' => 'admin',
            ],
        );
        $response = $this->client->getResponse();

        $this->assertEquals($response->getStatusCode(), 204);
        $this->assertEquals($response->getContent(), null);

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => 'registered@test.com']);

        $this->assertNotNull($user);
        $this->assertEquals('registered@test.com', $user->getEmail());
    }

    public function testUserSuccess(): void
    {
        $this->flushUser();

        $this->sendAuthenticatedRequest(
            method: Request::METHOD_GET,
            path: '/user',
        );
        $response = $this->client->getResponse();
        $payload = $this->denormalize($response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            [
                'id' => 'a93064a0-5818-4450-a86c-299c82676619',
                'email' => 'user@test.com',
            ],
            $payload,
        );
    }
}
