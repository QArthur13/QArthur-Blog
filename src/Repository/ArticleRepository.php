<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
        /* $queryBuilder = $this->createQueryBuilder('t')
                      ->where('t.title LIKE :term')
                      ->setParameter('term', '%'.$term.'%')
        ;

        $query = $queryBuilder->getQuery();

        return print_r($query->execute()); */

        /* $connexion = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM article t WHERE t.title LIKE :term';

        $stmt = $connexion->prepare($sql);
        $stmt->execute(['term' => '%'.$term.'%']);

        return $stmt->fetchAllAssociative(); */

        /* $query = $this->createQuery("SELECT t FROM article t WHERE t.title LIKE :text");
        $query->setPramater('text', '%'.$term['query'].'%');

        return $query->getResult(); */

        return $this->createQueryBuilder('t')
            ->where('t.title LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string|null $term
     * @return Article[]
     */
    /* public function findSearch(string $term)
    {
        $data = $this->createQueryBuilder('t');

        if ($term) {

            $data->andWhere('t.title LIKE :term')
                 ->setParameter('term', '%' . $term . '%')
            ;
        }

        return $data
            ->getQuery()
            ->getResult()
        ;
    } */

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
