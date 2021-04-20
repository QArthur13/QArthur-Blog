<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLike[]    findAll()
 * @method UserLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLike::class);
    }

    public function userLike($value)
    {
        return $this->createQueryBuilder('ul')
            ->innerJoin('ul.user', 'u', 'WITH', 'ul.user = :userId')
            ->setParameter('userId', $value)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function userLikeId($value)
    {
        return $this->createQueryBuilder('ul')
            ->innerJoin('ul.user', 'u', 'WITH', 'ul.user = :userId')
            ->setParameter('userId', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return UserLike[] Returns an array of UserLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserLike
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
