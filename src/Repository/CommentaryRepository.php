<?php

namespace App\Repository;

use App\Entity\Commentary;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentary[]    findAll()
 * @method Commentary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentary::class);
    }

    public function userComment($value /* User $user */)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u', 'WITH', 'c.user = :userId')
            ->setParameter('userId', $value)
            ->orderBy('c.date', 'desc')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function userCommentId($value /* User $user */)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u', 'WITH', 'c.user = :userId')
            ->setParameter('userId', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function eachCommentary(int $artitcleID)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.article', 'a', 'WITH', 'c.article = :id')
            ->setParameter('id', $artitcleID)
            ->where('c.approve = TRUE')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Commentary[] Returns an array of Commentary objects
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
    public function findOneBySomeField($value): ?Commentary
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
