<?php
declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    protected KernelBrowser $client;

    /**
     * @throws ToolsException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $container = $this->client->getKernel()->getContainer();

        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $metadata = $doctrine->getManager()->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);
    }

    public function testCreateUserSuccessfully(): void
    {
        // Given
        $userData = [
            'username' => 'testuser1234123',
            'email' => 'testuser@example.com',
            'password' => 'GurloxChuj12321!!@!@',
        ];

        // When
        $this->client->request('POST', '/user', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($userData));

        // Then
        static::assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        static::assertJson($this->client->getResponse()->getContent());
        static::assertStringContainsString('User created!', $this->client->getResponse()->getContent());
    }

    public function testCreateUserWithExistingEmail(): void
    {
        // Given
        $existingUserData = [
            'username' => 'existinguser',
            'email' => 'existinguser123@example.com',
            'password' => 'password',
        ];
        $this->client->request('POST', '/user', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($existingUserData));

        // When
        $this->client->request('POST', '/user', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($existingUserData));

        // Then
        static::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        static::assertJson($this->client->getResponse()->getContent());
        static::assertStringContainsString('User with email existinguser123@example.com already exists!', $this->client->getResponse()->getContent());
    }

    public function testDatabaseHost(): void
    {
        // Given
        $container = $this->client->getContainer();

        // When
        $doctrine = $container->get('doctrine');
        $connection = $doctrine->getConnection();
        $params = $connection->getParams();

        // Then
        static::assertArrayHasKey('host', $params);
        echo 'Database host: ' . $params['host'];
    }
}