<?php

declare(strict_types=1);

namespace App\Tests\functional\api;

final class StatusControllerTest extends ApiTestCase
{
    public function testStatusEndpointReturnsOk(): void
    {
        $this->client->request('GET', '/status');
        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('status', $responseData);
        $this->assertSame('ok', $responseData['status']);
    }
}
