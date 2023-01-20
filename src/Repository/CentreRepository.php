<?php

namespace App\Repository;

use App\Entity\Centre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Centre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Centre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Centre[]    findAll()
 * @method Centre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centre::class);
    }
    public function getCentresByType()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('t.nomTypeCE as label', 'COUNT(c) as y')
            ->from('App\Entity\Centre' ,'c')
            ->leftJoin('c.typeCE' , 't')
            ->groupBy('t.nomTypeCE');
        ;
        return $query->getQuery()->getResult();
    }

    public function getDashboardData()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('r.nomRegion as region', 'd.nomDepartement as departement', 'c2.nomCommune as commune', 'c.nomCE as centre', 
            'i.nomIndicateur as indicateur', 'f.annee as annee', 't.nomTypeCE as typeCE','z.nomZoneImpCE as zoneCE', 'f.nombre as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.centre' , 'c')
            ->leftJoin('c.commune' , 'c2')
            ->leftJoin('c2.departement' , 'd')
            ->leftJoin('d.region' , 'r')
            ->leftJoin('f.indicateur' , 'i')
            ->leftJoin('c.typeCE' , 't')
            ->leftJoin('c.zoneImCE' , 'z')
           ;
        ;
        return $query->getQuery()->getResult();
    }

    public function getDashboardDataByRegion($indicateur)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('r.nomRegion as region', 'i.nomIndicateur as indicateur', 'f.annee as annee', 'SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.centre' , 'c')
            ->leftJoin('c.commune' , 'c2')
            ->leftJoin('c2.departement' , 'd')
            ->leftJoin('d.region' , 'r')
            ->leftJoin('f.indicateur' , 'i')
            ->andwhere('i.id = :indicateur')
            ->setParameter('indicateur', $indicateur)
            ->groupBy('r.nomRegion, f.annee');
           ;
        ;
        return $query->getQuery()->getResult();
    }
    public function getDashboardDataByDepartement($indicateur)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('r.nomRegion as region', 'd.nomDepartement as departement', 'i.nomIndicateur as indicateur', 'f.annee as annee', 'SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.centre' , 'c')
            ->leftJoin('c.commune' , 'c2')
            ->leftJoin('c2.departement' , 'd')
            ->leftJoin('d.region' , 'r')
            ->leftJoin('f.indicateur' , 'i')
            ->andwhere('i.id = :indicateur')
            ->setParameter('indicateur', $indicateur)
            ->groupBy('d.id, f.annee');
           ;
        ;
        return $query->getQuery()->getResult();
    }
    public function getDashboardDataByCommune($indicateur)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('r.nomRegion as region', 'd.nomDepartement as departement', 'c2.nomCommune as commune', 'i.nomIndicateur as indicateur', 'f.annee as annee', 'SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.centre' , 'c')
            ->leftJoin('c.commune' , 'c2')
            ->leftJoin('c2.departement' , 'd')
            ->leftJoin('d.region' , 'r')
            ->leftJoin('f.indicateur' , 'i')
            ->andwhere('i.id = :indicateur')
            ->setParameter('indicateur', $indicateur)
            ->groupBy('c2.id, f.annee');
           ;
        ;
        return $query->getQuery()->getResult();
    }

    public function getDashboardDataByCentre($indicateur)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
        ->select('r.nomRegion as region', 'd.nomDepartement as departement', 'c2.nomCommune as commune', 'c.nomCE as centre', 
        'i.nomIndicateur as indicateur', 'f.annee as annee', 't.nomTypeCE as typeCE','z.nomZoneImpCE as zoneCE', 'SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.centre' , 'c')
            ->leftJoin('c.commune' , 'c2')
            ->leftJoin('c2.departement' , 'd')
            ->leftJoin('d.region' , 'r')
            ->leftJoin('f.indicateur' , 'i')
            ->leftJoin('c.typeCE' , 't')
            ->leftJoin('c.zoneImCE' , 'z')
            ->andwhere('i.id = :indicateur')
            ->setParameter('indicateur', $indicateur)
            ->groupBy('c.id, f.annee');
           ;
        ;
        return $query->getQuery()->getResult();
    }

    public function getNaissanceByAnneeEncour($indicateur1 , $indicateur2, $annee)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
        ->select('SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.indicateur' , 'i')
            ->andwhere('f.annee = :annee and (i.id = :indicateur1 or i.id = :indicateur2)')
            ->setParameter('indicateur1', $indicateur1)
            ->setParameter('indicateur2', $indicateur2)
            ->setParameter('annee', $annee)
            ;
           ;
        ;
        return $query->getQuery()->getResult();
    }

    public function getNombreFaitByAnneeEncour($indicateur, $annee)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
        ->select('SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.indicateur' , 'i')
            ->andwhere('f.annee = :annee and i.id = :indicateur')
            ->setParameter('indicateur', $indicateur)
            ->setParameter('annee', $annee)
            ;
           ;
        ;
        return $query->getQuery()->getResult();
    }

    public function getDonneeParAnneeParIndicateurParRegion($indicateur, $annee, $region)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select( 'SUM(f.nombre) as nombre' )
            ->from('App\Entity\FaitEC' ,'f')
            ->leftJoin('f.centre' , 'c')
            ->leftJoin('c.commune' , 'c2')
            ->leftJoin('c2.departement' , 'd')
            ->leftJoin('d.region' , 'r')
            ->leftJoin('f.indicateur' , 'i')
            ->andwhere('f.annee = :annee and i.id = :indicateur and r.id = :region')
            ->setParameter('indicateur', $indicateur)
            ->setParameter('annee', $annee)
            ->setParameter('region', $region)
            ->groupBy('r.id');
           ;
        ;
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Centre[] Returns an array of Centre objects
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

    /*
    public function findOneBySomeField($value): ?Centre
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
