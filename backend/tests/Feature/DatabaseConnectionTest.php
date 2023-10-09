<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\DBAL\Connection;

class DatabaseConnectionTest extends WebTestCase
{
    public function testDatabaseConnection()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $databaseUrl = $_ENV['DATABASE_URL'] ?? null;
        var_dump('Database URL: ' . $databaseUrl);
        /** @var Connection $connection */
        $connection = $container->get('doctrine')->getConnection();

        // Sprawdzenie, czy połączenie z bazą danych jest aktywne
        $this->assertTrue($connection->isConnected());

        // Alternatywnie, możesz wykonać prosty zapytanie SQL, aby sprawdzić połączenie
        $result = $connection->fetchColumn('SELECT 1');
        $this->assertEquals(1, $result);
    }
}
