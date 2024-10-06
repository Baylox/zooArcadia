<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Role;


class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $roles = [
            'ROLE_USER',
            'ROLE_ADMIN',
            'ROLE_VETERINAIRE',
            'ROLE_EMPLOYE',
        ];

        foreach ($roles as $roleName) {
            $role = new Role();
            $role->setLabel($roleName);
            $manager->persist($role);
        }

        $manager->flush();
    }
}