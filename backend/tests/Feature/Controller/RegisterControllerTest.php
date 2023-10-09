<?php
declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{

    public function testSuccessfulRegistration(): void
    {
        // Given
        $client = static::createClient();
        $data = [
            'registration_form' => [
                'username' => 'testuser',
                'email' => 'testuser@example.com',
                'plainPassword' => [
                    'first' => 'testpassword',
                    'second' => 'testpassword',
                ]
            ],
        ];

        // When
        $crawler = $client->request('POST', '/register', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        // Then
        $response = $client->getResponse();
        self::assertEquals(201, $response->getStatusCode());
        self::assertStringContainsString('Registration successful!', $response->getContent());
    }


    public function testRegistrationWithExistingEmail(): void
    {
        // Given
        $client = static::createClient();
        $data = [
            'registration_form' => [
                'username' => 'testuser',
                'email' => 'testuser@example.com',
                'plainPassword' => [
                    'first' => 'testpassword',
                    'second' => 'testpassword',
                ]
            ],
        ];
        // When
        $crawler = $client->request('POST', '/register', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        // Then
        $response = $client->getResponse();
        if ($response->getStatusCode() === 500) {
            var_dump($response->getContent());
        }
        self::assertEquals(400, $response->getStatusCode());
        // Upewnij się, że ten komunikat jest zwracany przez kontroler
        self::assertStringContainsString('This email is already used.', $response->getContent());
    }

    public function testRegistrationWithInvalidEmail(): void
    {
        // Given
        $client = static::createClient();
        $data = [
            'registration_form' => [
                'username' => 'testuser',
                'email' => 'testuser@example.com',
                'plainPassword' => [
                    'first' => 'testpassword',
                    'second' => 'testpassword',
                ]
            ],
        ];
        // When
        $crawler = $client->request('POST', '/register', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        // Then
        $response = $client->getResponse();
        self::assertEquals(400, $response->getStatusCode());
        // Upewnij się, że ten komunikat jest zwracany przez kontroler
        self::assertStringContainsString('Invalid email format.', $response->getContent());
    }


}
