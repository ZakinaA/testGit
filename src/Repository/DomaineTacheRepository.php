<?php

namespace App\Repository;

use App\Entity\DomaineTache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DomaineTache|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomaineTache|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomaineTache[]    findAll()
 * @method DomaineTache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomaineTacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomaineTache::class);
    }

    // /**
    //  * @return DomaineTache[] Returns an array of DomaineTache objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DomaineTache
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
