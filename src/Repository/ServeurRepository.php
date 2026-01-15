<?php

namespace App\Repository;

use App\Entity\Serveur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serveur>
 */
class ServeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serveur::class);
    }

    //    /**
    //     * @return Serveur[] Returns an array of Serveur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Serveur
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function CountTotalByRestaurant($restaurant): int
    {
        return $this->createQueryBuilder('s')
             ->select('COUNT(s.id)')
             ->andWhere('s.restaurant = :restaurant')
             ->setParameter('restaurant', $restaurant)
             ->getQuery()
             ->getSingleScalarResult();
    }

    public function SumTotalByRestaurantServeur($restaurant): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('Sum(r.salaire)')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }
}
