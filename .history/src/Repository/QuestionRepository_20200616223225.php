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
    public function findAllByTitleAndContent($query): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT q
            FROM App\Entity\Question q
            WHERE q.title LIKE :query OR q.content LIKE :query 
            ORDER BY q.date DESC'
        )->setParameter('query', $query);

        // returns an array of Product objects
        return $query->getResult();
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
    public function findOneBySomeField($value): ?Question
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
