<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameters([
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            'host' => 'localhost',
            'port' => 8000
        ]);
    }

    public function testRegister(): void
    {
        $this->client->request('POST', '/api/register', content: json_encode([
            'email' => 'email@example.com',
            'username' => 'username',
            'password' => 'password'
        ]));

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertStringContainsString('User created!', $response->getContent());
    }
}
