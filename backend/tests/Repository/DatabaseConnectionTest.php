<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseConnectionTest extends KernelTestCase
{
    private \Doctrine\ORM\EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        if ($this->entityManager === null) {
            throw new \Exception("Entity Manager is null");
        }
    }

    public function testDatabaseUrl(): void
    {
        $databaseUrl = $_ENV['DATABASE_URL'] ?? null;
        var_dump("Database URL: " . $databaseUrl);
        $this->assertNotEmpty($databaseUrl);
    }

    public function testDatabaseConnection(): void
    {
        // Wydrukuj wartość DATABASE_URL
        $databaseUrl = $_ENV['DATABASE_URL'] ?? null;
        var_dump('Database URL: ' . $databaseUrl);

        // Reszta twojego kodu testowego
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        $this->assertTrue(is_array($users));
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        // Zalecane, aby uniknąć wycieków pamięci
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
