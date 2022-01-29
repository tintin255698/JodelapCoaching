<?php

namespace App\Repository;

use App\Entity\Coffret;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Coffret|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coffret|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coffret[]    findAll()
 * @method Coffret[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoffretRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coffret::class);
    }

    // /**
    //  * @return Coffret[] Returns an array of Coffret objects
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
    public function findOneBySomeField($value): ?Coffret
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
