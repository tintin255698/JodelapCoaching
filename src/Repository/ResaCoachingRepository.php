<?php

namespace App\Repository;

use App\Entity\ResaCoaching;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResaCoaching|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResaCoaching|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResaCoaching[]    findAll()
 * @method ResaCoaching[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResaCoachingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResaCoaching::class);
    }

   /**
    //  * @return ResaCoaching[] Returns an array of ResaCoaching objects
    //  */

    public function numeroCommande()
    {
        return $this->createQueryBuilder('r')
            ->select('r.id')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }


    public function derniereCommande($user)
    {
        return $this->createQueryBuilder('r')
            ->select('r.numeroDeCommande')
            ->orderBy('r.numeroDeCommande', 'DESC')
            ->andWhere('r.user = :num')
            ->setParameter('num', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getScalarResult()
            ;
    }



    /*
    public function findOneBySomeField($value): ?ResaCoaching
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
