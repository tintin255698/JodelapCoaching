<?php

namespace App\Repository;

use App\Entity\CoachingTarif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoachingTarif|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoachingTarif|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoachingTarif[]    findAll()
 * @method CoachingTarif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoachingTarifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoachingTarif::class);
    }

    // /**
    //  * @return CoachingTarif[] Returns an array of CoachingTarif objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CoachingTarif
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
