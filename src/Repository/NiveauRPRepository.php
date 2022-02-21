<?php

namespace App\Repository;

use App\Entity\NiveauRP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NiveauRP|null find($id, $lockMode = null, $lockVersion = null)
 * @method NiveauRP|null findOneBy(array $criteria, array $orderBy = null)
 * @method NiveauRP[]    findAll()
 * @method NiveauRP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauRPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauRP::class);
    }

    // /**
    //  * @return NiveauRP[] Returns an array of NiveauRP objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NiveauRP
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
