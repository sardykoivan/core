<?php

declare(strict_types=1);

namespace App\Tests\functional\api;

use Symfony\Component\HttpFoundation\Request;

final class StatusControllerTest extends ApiTestCase
{
    public function testStatusOk(): void
    {
        $this->sendRequest(Request::METHOD_GET, '/status');

        $response = $this->client->getResponse();
        $responseData = $this->denormalize($response);

        $this->asserttrue($response->isOk());
        $this->assertEquals(['status' => 'ok'], $responseData);
    }
}
