<?php

namespace App\Repository;

use App\Entity\Boisson;
use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\TypePlat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    //    /**
    //     * @return Commande[] Returns an array of Commande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function CountTotalByRestaurantCommandes(Restaurant $restaurant): int
    {
        return $this->createQueryBuilder('r')
            ->select('Count(r.id)')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function SumTotalByRestaurantCommandes(Restaurant $restaurant): float
    {
        return $this->createQueryBuilder('r')
            ->select('Sum(r.prixCommande)')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMostPopularMenuByRestaurant(Restaurant $restaurant): ?array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('p.libProduit as nom', 'COUNT(p.id) as total')
            ->join('c.produits', 'p')
            ->where('c.restaurant = :restaurant')
            ->andWhere($qb->expr()->isInstanceOf('p', Menu::class))
            ->setParameter('restaurant', $restaurant)
            ->groupBy('p.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMostPopularBoissonByRestaurant(Restaurant $restaurant): ?array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('p.libProduit as nom', 'COUNT(p.id) as total')
            ->join('c.produits', 'p')
            ->where('c.restaurant = :restaurant')
            ->andWhere($qb->expr()->isInstanceOf('p', Boisson::class))
            ->setParameter('restaurant', $restaurant)
            ->groupBy('p.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMostPopularPlatByRestaurant(Restaurant $restaurant): ?array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('p.libProduit as nom', 'COUNT(p.id) as total')
            ->join('c.produits', 'p')
            ->innerJoin(Plat::class, 'plat', 'WITH', 'plat.id = p.id')
            ->join('plat.typePlat', 'tp')
            ->where('c.restaurant = :restaurant')
            ->andWhere('tp.id = :typeId')
            ->setParameter('restaurant', $restaurant)
            ->setParameter('typeId', 2)
            ->groupBy('p.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findMostPopularDessertByRestaurant(Restaurant $restaurant): ?array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->select('p.libProduit as nom', 'COUNT(p.id) as total')
            ->join('c.produits', 'p')
            ->innerJoin(Plat::class, 'plat', 'WITH', 'plat.id = p.id')
            ->join('plat.typePlat', 'tp')
            ->where('c.restaurant = :restaurant')
            ->andWhere('tp.id = :typeId')
            ->setParameter('restaurant', $restaurant)
            ->setParameter('typeId', 3)
            ->groupBy('p.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
