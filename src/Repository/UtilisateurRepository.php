<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

   /**
     * Récupère les utilisateurs ayant le rôle 'ROLE_EMPLOYE' en utilisant une requête SQL brute
     */
    public function findEmployes(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM utilisateur WHERE JSON_CONTAINS(roles, :role)'; // On utilise la fonction JSON_CONTAINS pour vérifier si le rôle est présent
        $stmt = $conn->prepare($sql); // Requête préparée oblige 
        $resultSet = $stmt->executeQuery(['role' => json_encode('ROLE_EMPLOYE')]); // On encode le rôle en JSON

        return $resultSet->fetchAllAssociative(); // On récupère les résultats sous forme de tableau associatif
    }

    /**
     * Récupère les utilisateurs ayant le rôle 'ROLE_VETERINAIRE' 
     */
    public function findVeterinaires(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM utilisateur WHERE JSON_CONTAINS(roles, :role)';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['role' => json_encode('ROLE_VETERINAIRE')]);

        return $resultSet->fetchAllAssociative();
    }
}

    //    /**
    //     * @return Utilisateur[] Returns an array of Utilisateur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Utilisateur
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

