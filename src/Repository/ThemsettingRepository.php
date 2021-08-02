<?php

namespace App\Repository;

use App\Entity\Themsetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Themsetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Themsetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Themsetting[]    findAll()
 * @method Themsetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemsettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Themsetting::class);
    }

    // /**
    //  * @return Themsetting[] Returns an array of Themsetting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Themsetting
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
