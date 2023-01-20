<?php

namespace App\Repository;

use App\Entity\FaitEC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FaitEC|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaitEC|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaitEC[]    findAll()
 * @method FaitEC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaitECRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaitEC::class);
    }

    // /**
    //  * @return FaitEC[] Returns an array of FaitEC objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findFaitEC($centre)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('f')
            ->from('App\Entity\FaitEC' ,'f')
            //->innerJoin('i.indicateurBase' , 'ib')
            ->Where('f.centre = :centre')
            ->setParameter('centre', $centre)
            ->orderBy('f.id', 'ASC')
        ;
        return $query->getQuery()->getResult();
    }
    /*
    public function findOneBySomeField($value): ?FaitEC
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
