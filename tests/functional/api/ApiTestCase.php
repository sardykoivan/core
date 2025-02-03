<?php

declare(strict_types=1);

namespace App\Tests\functional\api;

use App\Kernel;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected SerializerInterface $serializer;

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createClient(['environment' => 'test']);
        $this->denormalizer = $this->getContainer()->get(DenormalizerInterface::class);
        $this->serializer = $this->getContainer()->get(SerializerInterface::class);
    }

    protected function sendAuthenticatedRequest(
        string $method,
        string $path,
        ?array $data = [],
        ?array $queryParams = null,
        ?array $headers = [],
        ?array $server = [],
    ): Response {
        $headers['Authorization'] = 'Bearer ' . $this->getJwtToken();

        return $this->sendRequest($method, $path, $data, $queryParams, $headers, $server);
    }

    protected function sendRequest(
        string $method,
        string $path,
        ?array $data = [],
        ?array $queryParams = null,
        ?array $headers = [],
        ?array $server = [],
        ?string $prefix = null,
    ): Response {
        $queryString = null;

        foreach ($headers as $key => $value) {
            $server['HTTP_' . strtoupper(strtr($key, '-', '_'))] = $value;
        }

        if ($queryParams) {
            $queryString = '?' . http_build_query($queryParams);
        }


        $this->client->jsonRequest(
            method: $method,
            uri: sprintf('/%s%s%s', $prefix ?? 'api/', ltrim($path, '/'), $queryString),
            parameters: $data,
            server: $server,
        );

        return $this->client->getResponse();
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     *
     * @return T
     *
     * @throws JsonException
     * @throws ExceptionInterface
     */
    protected function denormalizeResponse(string $className): object
    {
        $content = json_decode($this->client->getResponse()->getContent(), flags: JSON_THROW_ON_ERROR);

        return $this->denormalizer->denormalize($content, $className);
    }

    protected function parseResponseContent(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }

    /**
     * @dataProvider provideSuccessfulTestData
     */
    protected function getJwtToken(): string
    {
        $this->sendRequest(
            'POST',
            '/passport/auth',
            $this->getSuccessfulAuthenticationData(),
            [],
            []
        );
        $response = $this->client->getResponse();
        $data = $this->parseResponseContent($response);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $this->fail('Unsuccessful JWT-authorization.');
        }

        return $data['token'];
    }

    protected function getSuccessfulAuthenticationData(): array
    {
        return
            ['email' => 'ivan@game.com', 'password' => 'admin']
            ;
    }
}
