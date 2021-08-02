<?php

namespace App\Repository;

use App\Entity\Leaves;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Leaves|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leaves|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leaves[]    findAll()
 * @method Leaves[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeavesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leaves::class);
    }

    // /**
    //  * @return Leaves[] Returns an array of Leaves objects
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
    public function findOneBySomeField($value): ?Leaves
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
