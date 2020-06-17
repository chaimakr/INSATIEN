<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    
    /**
    * @return Question[]
    */
    public function findRecent()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

     /**
     * @return Question[]
     */
    public function findMyQuestionInSpecificClass($Class,$User): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT q
            FROM App\Entity\Question q
            WHERE q.owner = :User AND q.class = :Class
            ORDER BY q.date DESC'
        )->setParameter('User', $User)
        ->setParameter('Class', $Class);

        // returns an array of Product objects
        return $query->getResult();
    }



    // /**
    //  * @return Question[] Returns an array of Question objects
    //  */
    
    /*public function findByTitleAndContent($query)
    {
            $qb=$this->createQueryBuilder('q')
            ->andWhere(' q.title = :query OR q.content = :query ')
            ->setParameter('query', $query)
            ->orderBy('q.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }*/
    

   // /**
   //  * @return Question[] Returns an array of Question objects
    // */
    /*public function findOneBySomeField($value): ?Question
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }*/
    
}
