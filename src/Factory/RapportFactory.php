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
            'titre' => self::faker()->paragraph(25),  // Génère un titre aléatoire pour le rapport
            'utilisateur' => UtilisateurFactory::new(), // Crée et associe une nouvelle instance d'utilisateur
            'date_rapport' => self::faker()->dateTimeBetween('-1 year', 'now'),  // Date du rapport aléatoire entre l'année dernière et aujourd'hui
            'details' => self::faker()->optional()->paragraph(),  // Détails du rapport 
            'nomNourriture' => self::faker()->word(),  // Nom de la nourriture aléatoire
            'quantiteNourriture' => self::faker()->randomFloat(2, 0.1, 5.0),  // Quantité de nourriture aléatoire entre 0.1 et 5.0
            'changementAlimentation' => self::faker()->boolean(),  // Booléen indiquant s'il y a eu un changement d'alimentation
        ];
    }
}
