<?php

namespace App\Factory;

use App\Entity\Espece;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Espece>
 */
final class EspeceFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Espece::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'typeEspece' => self::faker()->word(),  // Nom de l'espèce aléatoire
            'evaluationExtinction' => self::faker()->randomElement([
                'En danger', 
                'Vulnérable', 
                'Quasi menacé',
                'Préoccupation mineure',
                'Non évalué'
            ]),
            'traitsCaracteristiques' => self::faker()->text(200),  // Description des traits
        ];
    }
}

