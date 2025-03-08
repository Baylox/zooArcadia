<?php

namespace App\Tests;

use App\Document\Horaire;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Teste la connexion à MongoDB pour les horaires (Test d'intégration)
 */
class MongoDBHoraireTest extends KernelTestCase
{
    public function testMongoDBHoraireInsertion(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var DocumentManager $dm */
        $dm = $container->get(DocumentManager::class);

        $horaire = new Horaire();
        $horaire->setJour("Lundi");
        $horaire->setHeureOuverture("09:00");
        $horaire->setHeureFermeture("18:00");

        $dm->persist($horaire);
        $dm->flush();

        $this->assertNotNull($horaire->getId(), 'L\'insertion de l\'horaire dans MongoDB a réussi.');
    }
}