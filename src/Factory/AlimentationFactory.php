<?php

namespace App\Factory;

use App\Entity\Alimentation;
use App\Factory\RapportFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Alimentation>
 */
final class AlimentationFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Alimentation::class;
    }

    /**
     * Définition des valeurs par défaut pour les alimentations
     */
    protected function defaults(): array|callable
    {
        return [
            'rapport' => RapportFactory::new(),  // Crée et associe une nouvelle instance de rapport
            'nom_nourriture' => self::faker()->word(),
            'quantiteNourriture' => self::faker()->randomFloat(2, 1, 100), 
            'commentaire_veterinaire' => self::faker()->optional()->paragraph(),
        ];
    }
}