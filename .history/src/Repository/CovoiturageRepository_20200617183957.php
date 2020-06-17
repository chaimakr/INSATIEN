<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Covoiturage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Covoiturage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Covoiturage[]    findAll()
 * @method Covoiturage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

     /**
      * @return Covoiturage[] Returns an array of Covoiturage objects
      */
    
    public function findRecent()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Covoiturage
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
