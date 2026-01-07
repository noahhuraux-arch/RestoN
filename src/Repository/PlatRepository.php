<?php

namespace App\Repository;

use App\Entity\Plat;
use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plat>
 */
class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }

    public function findAllTypes(Restaurant $restaurant)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('t')
            ->join('p.typePlat', 't')
            ->where('p.idRestau = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->orderBy('p.prixProduit', 'ASC')
            ->addOrderBy('p.libProduit', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return plat[] Returns an array of plat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?plat
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
