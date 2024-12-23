<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends WebTestCase
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
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');

        // Create a User fixture
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = (new User())
            ->setUsername('username')
            ->setEmail('email@example.com');
        $user->setPassword($passwordHasher->hashPassword($user, 'password'));

        $em->persist($user);
        $em->flush();
    }

    public function testLogin(): void
    {
         $this->client->request('POST', '/api/login_check', content: json_encode([
            'username' => 'email@example.com',
            'password' => 'password'
        ]));

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertStringContainsString('token', $response->getContent());
    }
}
