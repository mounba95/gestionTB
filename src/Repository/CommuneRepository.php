<?php

namespace App\Repository;

use App\Entity\Commune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Commune|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commune|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commune[]    findAll()
 * @method Commune[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommuneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commune::class);
    }

    // /**
    //  * @return Commune[] Returns an array of Commune objects
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
    public function findNomCommuneAndDepartement($nomCom, $departement)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('c.id')
            ->from('App\Entity\Commune' ,'c')
            //->innerJoin('i.indicateurBase' , 'ib')
            ->Where('c.nomCommune = :nom')
            ->setParameter('nom', $nomCom)
            ->orderBy('c.id', 'ASC')
        ;
        if ($departement) {
            $query->andwhere('c.departement = :departement ');
            $query ->setParameter('departement', $departement);
        }

        return $query->getQuery()->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Commune
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
