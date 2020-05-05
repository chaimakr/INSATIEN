<?php

namespace App\Repository;

use App\Entity\ContactMail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactMail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMail[]    findAll()
 * @method ContactMail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactMail::class);
    }

    // /**
    //  * @return ContactMail[] Returns an array of ContactMail objects
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
    public function findOneBySomeField($value): ?ContactMail
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
