<?php

namespace App\Repository;

use App\Entity\ResaCocaching;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResaCocaching|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResaCocaching|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResaCocaching[]    findAll()
 * @method ResaCocaching[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResaCocachingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResaCocaching::class);
    }

    // /**
    //  * @return ResaCocaching[] Returns an array of ResaCocaching objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResaCocaching
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
