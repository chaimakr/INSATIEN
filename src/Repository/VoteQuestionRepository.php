<?php

namespace App\Repository;

use App\Entity\VoteQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoteQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteQuestion[]    findAll()
 * @method VoteQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteQuestion::class);
    }

    // /**
    //  * @return VoteQuestion[] Returns an array of VoteQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoteQuestion
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
