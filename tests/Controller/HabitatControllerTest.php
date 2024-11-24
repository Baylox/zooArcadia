<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HabitatControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/habitat');

        // Vérifie que la page de l'index se charge avec succès
        $this->assertResponseIsSuccessful();
    }
}
