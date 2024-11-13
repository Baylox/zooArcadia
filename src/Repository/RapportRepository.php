<?php

namespace App\Repository;

use App\Entity\Rapport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Animal;
/**
 * @extends ServiceEntityRepository<Rapport>
 */
class RapportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rapport::class);
    }

        /**
     * Récupère les rapports associés à un animal spécifique.
     *
     * @param Animal $animal
     * @return Rapport[]
     */
    public function findByAnimal(Animal $animal): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.animal = :animal')
            ->setParameter('animal', $animal)
            ->orderBy('r.dateRapport', 'DESC') // Optionnel : trie par date décroissante
            ->getQuery()
            ->getResult();
    }
    
    //    /**
    //     * @return Rapport[] Returns an array of Rapport objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Rapport
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
