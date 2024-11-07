<?php

namespace App\DataFixtures;

use App\Document\Horaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;

class HoraireFixtures implements ODMFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $jours = [
            'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'
        ];

        foreach ($jours as $jour) {
            $horaire = new Horaire();
            $horaire->setJour($jour);
            $horaire->setHeureOuverture("09:00");
            $horaire->setHeureFermeture("17:00");

            $manager->persist($horaire);
        }

        $manager->flush();
    }
}
