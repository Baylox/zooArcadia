<?php

namespace App\Factory;

use App\Entity\Habitat;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Habitat>
 */
final class HabitatFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array
    {
        return [
            'nom' => self::faker()->randomElement(['Savane', 'Jungle', 'Marais']),
            'description' => self::faker()->sentence(),
            'typeHabitat' => self::faker()->randomElement(['Savane', 'Jungle', 'Marais']),
        ];
    }

    public static function class(): string
    {
        return Habitat::class;
    }
}




