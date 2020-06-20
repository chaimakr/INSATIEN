<?php

namespace App\Repository;

use App\Entity\QuizTry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuizTry|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizTry|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizTry[]    findAll()
 * @method QuizTry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizTryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizTry::class);
    }

    // /**
    //  * @return QuizTry[] Returns an array of QuizTry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizTry
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
