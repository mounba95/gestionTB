<?php

namespace App\Repository;

use App\Entity\ZoneImpCE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ZoneImpCE|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneImpCE|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneImpCE[]    findAll()
 * @method ZoneImpCE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneImpCERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneImpCE::class);
    }

    // /**
    //  * @return ZoneImpCE[] Returns an array of ZoneImpCE objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZoneImpCE
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
