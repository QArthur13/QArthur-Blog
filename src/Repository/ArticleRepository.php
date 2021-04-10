<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[]
     */
    public function findSearch(string $term)
    {
        return $this->createQueryBuilder('t')
            ->where('t.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function ascendingAutor(string $term)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.user', 'u', 'WITH', 'u.id = a.user')
            ->where('a.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('u.lastName, u.firstName', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function descendingAutor(string $term)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.user', 'u', 'WITH', 'u.id = a.user')
            ->where('a.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('u.lastName, u.firstName', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function ascendingDate(string $term)
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('a.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function descendingDate(string $term)
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function ascendingTitle(string $term)
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('a.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function descendingTitle(string $term)
    {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('a.title', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
