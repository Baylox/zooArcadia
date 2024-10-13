<?php

namespace App\Factory;

use App\Entity\Animal;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Animal>
 */
final class AnimalFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Animal::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'prenom' => self::faker()->firstName(),  // Génère un prénom aléatoire pour l'animal
            'etat' => self::faker()->randomElement(['en bonne santé', 'malade', 'blessé']),  // Choix d'un état aléatoire
            'sexe' => self::faker()->randomElement(['mâle', 'femelle']),  // Ajout d'un sexe aléatoire
            'description' => self::faker()->paragraph(),  // Ajout d'une description
            'dob' => self::faker()->dateTimeBetween('-10 years', 'now'),  // Utilise une instance de DateTime pour la date de naissance 
            'espece' => EspeceFactory::new(),  // Crée et associe une nouvelle instance d'Espece
        ];
    }
}
