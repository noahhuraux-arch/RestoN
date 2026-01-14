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
    public function findByRestaurantWithDetailsReservations(Restaurant $restaurant): array
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

    public function findFullDetailsReservation(int $id): Reservation
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.client', 'c')
            ->addSelect('c')
            ->leftJoin('r.table', 't')
            ->addSelect('t')
            ->leftJoin('r.restaurant', 'restau')
            ->addSelect('restau')
            ->leftJoin('restau.proprietaire', 'p')
            ->addSelect('p')
            ->leftJoin('restau.serveurs', 's')
            ->addSelect('s')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTodayByReservation(Restaurant $restaurant): array
    {
        $Today = new \DateTime();
        $Today->setTime(0, 0, 0);

        return $this->createQueryBuilder('r')
            ->leftJoin('r.client', 'c')
            ->addSelect('c')
            ->leftJoin('r.table', 't')
            ->addSelect('t')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->andWhere('r.date = :today')
            ->setParameter('today', $Today)
            ->orderBy('r.date', 'ASC')
            ->addOrderBy('r.heure', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchByClientName(Restaurant $restaurant, string $searchText): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.client', 'c')
            ->addSelect('c')
            ->leftJoin('r.table', 't')
            ->addSelect('t')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->orderBy('r.date', 'DESC')
            ->addOrderBy('r.heure', 'ASC');

        if ('' !== $searchText) {
            $qb->andWhere('c.nom LIKE :search OR c.prenom LIKE :search')
                ->setParameter('search', '%'.$searchText.'%');
        }

        return $qb->getQuery()->getResult();
    }

    public function CountTotalByRestaurantReservations(Restaurant $restaurant): int
    {
        return $this->createQueryBuilder('r')
            ->select('Count(r.id)')
            ->andWhere('r.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
