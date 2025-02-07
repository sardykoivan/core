<?php

declare(strict_types=1);

namespace Tests\functional\api;

use App\Tests\functional\api\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;

final class AuthenticationTest extends ApiTestCase
{
    public function testSuccessUserAuthentication(): void
    {
        $this->flushUser();

        $this->client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/auth',
            parameters: [
                'email' => 'user@test.com',
                'password' => 'admin',
            ],
        );
        $response = $this->client->getResponse();

        $this->assertTrue($response->isOk());
        $this->assertArrayHasKey('token', $this->denormalize($response));
    }

    public function testFailureUserAuthentication(): void
    {
        $this->client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/auth',
            parameters: [
                'email' => 'user@test.com',
                'password' => 'admin',
            ],
        );
        $response = $this->client->getResponse();
        $payload = $this->denormalize($response);

        $this->assertEquals($response->getStatusCode(), 401);
        $this->assertArrayHasKey('message', $payload);
        $this->assertEquals('Invalid credentials.', $payload['message']);
    }
}
