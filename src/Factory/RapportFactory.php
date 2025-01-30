<?php

namespace App\Factory;

use App\Entity\Rapport;
use App\Factory\UtilisateurFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Rapport>
 */
final class RapportFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Rapport::class;
    }

    /**
     * Définition des valeurs par défaut pour les rapports
     */
    protected function defaults(): array|callable
    {
        return [
            'titre' => substr(self::faker()->sentence(3), 0, 50),
            'utilisateur' => UtilisateurFactory::randomOrCreate(), // Utilise un utilisateur existant ou en crée un
            'date_rapport' => self::faker()->dateTimeBetween('-1 year', 'now'),
            'details' => self::faker()->optional()->paragraph(),
            'animal' => AnimalFactory::randomOrCreate(),
        ];
    }
}
