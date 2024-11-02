<?php

namespace App\Factory;

use App\Entity\Service;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Service>
 */
final class ServiceFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array
    {
        return [
            'nom' => self::faker()->sentence(3), // Génération d'un titre
            'description' => self::faker()->text(),
        ];
    }

    public static function class(): string
    {
        return Service::class;
    }
}