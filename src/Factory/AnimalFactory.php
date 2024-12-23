<?php

namespace App\Factory;

use App\Entity\Animal;
use App\Factory\EspeceFactory;
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

    protected function defaults(): array|callable
    {
        return [
            'prenom' => self::faker()->firstName(),  
            'etat' => self::faker()->randomElement(['en bonne santé', 'malade', 'blessé']),  
            'sexe' => self::faker()->randomElement(['mâle', 'femelle']),  
            'description' => self::faker()->paragraph(),  
            'dob' => self::faker()->dateTimeBetween('-10 years', 'now'),  
            'espece' => EspeceFactory::new(),  
            'habitat' => HabitatFactory::new(),
        ];
    }
}

