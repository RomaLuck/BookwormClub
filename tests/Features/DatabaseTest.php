<?php

namespace App\Tests\Features;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseTest extends KernelTestCase
{
    public function testConnection(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $container = $kernel->getContainer();
        $databaseService = $container->get('doctrine.dbal.default_connection');
        $this->assertNotNull($databaseService);

        try {
            $databaseService->executeQuery('SELECT 1');
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("Can not connect to: {$e->getMessage()}");
        }


        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
