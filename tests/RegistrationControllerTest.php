<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameters([
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            'host' => 'localhost',
            'port' => 8000
        ]);

        // Ensure we have a clean database
        $container = static::getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();
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
