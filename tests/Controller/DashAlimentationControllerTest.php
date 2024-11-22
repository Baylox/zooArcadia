<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DashAlimentationControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/dashboard/alimentation');

        // Vérifie que la page de l'index se charge avec succès
        $this->assertResponseIsSuccessful();
    }
}
