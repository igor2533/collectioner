<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Comments;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }





    /**
     * @return Item[] Returns an array of Item objects
     */

    public function findByAllSortDate()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.date_created', 'DESC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }





    /**
    * @return Item[] Returns an array of Item objects
    */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->orWhere('i.title like :val')
            ->orWhere('i.description like :val')
            ->setParameter('val', '%'.$value.'%')
            ->leftJoin('i.comments', 'comments')
            ->orWhere('comments.description like :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
