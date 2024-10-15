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
            'utilisateur' => UtilisateurFactory::new(), // Crée et associe une nouvelle instance d'utilisateur
            'date_rapport' => self::faker()->dateTimeBetween('-1 year', 'now'),  // Date du rapport aléatoire entre l'année dernière et aujourd'hui
            'details' => self::faker()->optional()->paragraph(),  // Détails du rapport 
        ];
    }
}
