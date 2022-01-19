<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    public function moyenne()
    {
        return $this->createQueryBuilder('c')
            ->select('AVG(c.note) as note')
            ->where('c.bool = :bool')
            ->setParameter('bool', 1)
            ->getQuery()
            ->getResult()
            ;
    }


    public function commentaire()
    {
        return $this->createQueryBuilder('c')
            ->select('c.note, c.contenu, u.FirstName, u.LastName'  )
            ->join('c.user', 'u')
            ->orderBy('c.id', 'DESC')
            ->setMaxResults(9)
            ->where('c.bool = :bool')
            ->setParameter('bool', 1)
            ->getQuery()
            ->getResult()
            ;
    }


}
