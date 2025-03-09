<?php

namespace App\Tests;

use App\Document\Avis;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Teste la connexion à MongoDB pour les Avis (Test d'intégration)
 */
class MongoDBConnectionTest extends KernelTestCase
{
    public function testMongoDBConnection(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var DocumentManager $dm */
        $dm = $container->get(DocumentManager::class);

        $avis = new Avis();
        $avis->setPseudo("TestUser");
        $avis->setCommentaire("Test de connexion MongoDB");

        $dm->persist($avis);
        $dm->flush();

        $this->assertNotNull($avis->getId(), 'L\'insertion dans MongoDB a réussi.');
        
        $dm->remove($avis);
        $dm->flush();
    }
}
