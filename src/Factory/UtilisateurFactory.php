<?php

namespace App\Factory;

use App\Entity\Utilisateur;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends PersistentProxyObjectFactory<Utilisateur>
 */
final class UtilisateurFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function class(): string
    {
        return Utilisateur::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'nom' => self::faker()->text(25),
            'password' => 'password',
            'prenom' => self::faker()->text(25),
            'roles' => [],
        ];
    }


    /**
     * Initialisation de l'objet Utilisateur après son instanciation.
     * 
     * @return static
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Utilisateur $utilisateur): void {
                // Si un mot de passe est défini, on le hache avant de le sauvegarder
                if ($utilisateur->getPassword()) {
                    $hashedPassword = $this->passwordHasher->hashPassword($utilisateur, $utilisateur->getPassword());
                    $utilisateur->setPassword($hashedPassword);
                }
            });
    }
}
