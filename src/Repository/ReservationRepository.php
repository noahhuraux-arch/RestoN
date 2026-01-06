<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Récupère toutes les réservations d'un restaurant avec les données
     * client et table jointes (Eager Loading) pour éviter le N+1.
     *
     * * @return Reservation[]
     */
    public function findByRestaurantWithDetails(Restaurant $restaurant): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.client', 'c')
            ->addSelect('c')
            ->leftJoin('r.table', 't')
            ->addSelect('t')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->orderBy('r.date', 'ASC')
            ->addOrderBy('r.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
