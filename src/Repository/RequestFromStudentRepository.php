<?php

namespace App\Repository;

use App\Entity\RequestFromStudent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestFromStudent|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestFromStudent|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestFromStudent[]    findAll()
 * @method RequestFromStudent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestFromStudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestFromStudent::class);
    }

    // /**
    //  * @return RequestFromStudent[] Returns an array of RequestFromStudent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestFromStudent
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
