<?php

namespace App\Repository;

use App\Entity\UserShare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserShare|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserShare|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserShare[]    findAll()
 * @method UserShare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserShareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserShare::class);
    }

    public function userShare($value)
    {
        return $this->createQueryBuilder('us')
            ->innerJoin('us.user', 'u', 'WITH', 'us.user = :userId')
            ->setParameter('userId', $value)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function userShareId($value)
    {
        return $this->createQueryBuilder('us')
            ->innerJoin('us.user', 'u', 'WITH', 'us.user = :userId')
            ->setParameter('userId', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return UserShare[] Returns an array of UserShare objects
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
    public function findOneBySomeField($value): ?UserShare
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
