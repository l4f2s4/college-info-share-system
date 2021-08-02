<?php

namespace App\Repository;

use App\Entity\LeavesType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeavesType|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeavesType|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeavesType[]    findAll()
 * @method LeavesType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavesTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeavesType::class);
    }

    // /**
    //  * @return LeavesType[] Returns an array of LeavesType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LeavesType
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
