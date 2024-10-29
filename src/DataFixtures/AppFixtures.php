<?php

namespace App\DataFixtures;

use App\Factory\AnimalFactory;
use App\Factory\UtilisateurFactory;
use App\Factory\EspeceFactory;
use App\Factory\RapportFactory;
use App\Factory\AlimentationFactory; 
use App\Entity\Habitat;
use App\Factory\ImageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture {
    public function load(ObjectManager $manager)
    {
        // Créer 10 utilisateurs
        UtilisateurFactory::createMany(10);
        
        // Créer 1 utilisateur admin
        UtilisateurFactory::new([
            'email' => 'test@test.com',
            'roles' => ['ROLE_ADMIN'],
        ])->create();
        
        // Créer 10 espèces
        EspeceFactory::new()->createMany(10);

        // Créer manuellement les habitats et ajouter des références
        $savane = new Habitat();
        $savane->setNom('Savane');
        $savane->setDescription('Un grand espace herbeux avec des arbres clairsemés.');
        $savane->setTypeHabitat('Savane');
        $manager->persist($savane);
        $this->addReference('habitat_savane', $savane);

        $jungle = new Habitat();
        $jungle->setNom('Jungle');
        $jungle->setDescription('Une forêt dense avec une grande diversité animale.');
        $jungle->setTypeHabitat('Jungle');
        $manager->persist($jungle);
        $this->addReference('habitat_jungle', $jungle);

        $marais = new Habitat();
        $marais->setNom('Marais');
        $marais->setDescription('Un environnement humide et marécageux.');
        $marais->setTypeHabitat('Marais');
        $manager->persist($marais);
        $this->addReference('habitat_marais', $marais);

        // Persister les habitats avant de les utiliser dans AnimalFactory
        $manager->flush();

        // Créer 5 rapports
        RapportFactory::createMany(10);
        
        // Créer 10 animaux, en associant les animaux à un des trois habitats
        AnimalFactory::new()->createMany(3, ['habitat' => $savane]);
        AnimalFactory::new()->createMany(4, ['habitat' => $jungle]);
        AnimalFactory::new()->createMany(3, ['habitat' => $marais]);

        // Créer 10 alimentations
        AlimentationFactory::createMany(10);

         // Créer 15 images associées à un animal aléatoire
         ImageFactory::new()->createMany(15, [
            'animal' => AnimalFactory::random() // Associe chaque image à un animal existant aléatoire
        ]);

        $manager->flush();
    }
}