<?php

namespace App\DataFixtures;

use App\Factory\UtilisateurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager)
    {
    // Créer 10 utilisateurs
    UtilisateurFactory::createMany(10);
    $manager->flush();
    }
}