<?php

declare(strict_types=1);

namespace App\Tests\functional\api;

use App\Entity\User;
use App\Tests\data\UserData;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{
    private const ADMIN_PASSWORD = 'admin';

    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createClient(['environment' => 'test']);
        $this->em = $this->getContainer()->get(EntityManagerInterface::class);

        $this->resetDatabase();
    }

    public function tearDown(): void
    {
        $this->resetDatabase();
        parent::tearDown();
    }

    protected function sendAuthenticatedRequest(
        string $method,
        string $path,
        ?array $data = [],
        ?array $queryParams = null,
        ?array $headers = [],
        ?array $server = [],
    ): Response {
        $headers['Authorization'] = $this->getAuthorizationHeader($this->receiveToken());

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

    public function denormalize(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }

    private function resetDatabase(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    protected function getUser(): User
    {
        return UserData::getUser();
    }

    protected function flushUser(): void
    {
        $this->em->persist($this->getUser());
        $this->em->flush();
    }

    protected function getAuthorizationHeader(string $token): string
    {
        return 'Bearer ' . $token;
    }

    protected function receiveToken(): string
    {
        $user = $this->getUser();

        $this->client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/auth',
            parameters: [
                'email' => $user->getEmail(),
                'password' => self::ADMIN_PASSWORD,
            ],
        );

        $data = $this->denormalize($this->client->getResponse());
        if (!isset($data['token'])) {
            $this->fail('jwt auth error');
        }

        return $data['token'];
    }
}
