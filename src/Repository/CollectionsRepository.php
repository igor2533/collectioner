<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Collections;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Collections|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collections|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collections[]    findAll()
 * @method Collections[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollectionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collections::class);
    }

    // /**
    //  * @return Collection[] Returns an array of Collection objects
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

    /**
     * @return Collections[] Returns an array of Item objects
     */

    public function findByAllSortCountItems()
    {
        return $this->createQueryBuilder('c')

//            ->leftJoin('c.items', 'item')
//            ->orderBy('item', 'DESC')


->setMaxResults(6)

//            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Collection
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
