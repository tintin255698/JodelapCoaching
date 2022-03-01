<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

     /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */

    public function evenementAccueil()
    {
        $date =  new \DateTime('now');
        return $this->createQueryBuilder('e')
            ->andWhere('e.boolean = :val')
            ->setParameter('val', 1)
            ->andWhere('e.dateTime > :date' )
            ->setParameter('date', $date)
            ->orderBy('e.dateTime', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function evenementIndex()
    {
        $date =  new \DateTime('now');
        return $this->createQueryBuilder('e')
            ->andWhere('e.boolean = :val')
            ->setParameter('val', 1)
            ->andWhere('e.dateTime > :date' )
            ->setParameter('date', $date)
            ->orderBy('e.dateTime', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
