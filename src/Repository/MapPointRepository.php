<?php

namespace App\Repository;

use App\Entity\MapPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MapPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapPoint[]    findAll()
 * @method MapPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapPoint::class);
    }

    // /**
    //  * @return MapPoint[] Returns an array of MapPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MapPoint
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
