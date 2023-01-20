<?php

namespace App\Repository;

use App\Entity\TypeCE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeCE|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCE|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCE[]    findAll()
 * @method TypeCE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCE::class);
    }

    // /**
    //  * @return TypeCE[] Returns an array of TypeCE objects
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
    public function findOneBySomeField($value): ?TypeCE
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
