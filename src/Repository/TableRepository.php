<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\Table;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Table>
 */
class TableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Table::class);
    }

    public function findRestaurantWithReservations(Restaurant $restaurant): array
    {
        return $this->createQueryBuilder('t')
            ->addSelect('r')
            ->leftJoin('t.reservations', 'r')
            ->where('t.restaurant = :restaurant')
            ->setParameter('restaurant', $restaurant)
            ->orderBy('t.numero', 'ASC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Table[] Returns an array of Table objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Table
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Trouve UNE table disponible pour un créneau donné.
     *
     * @param \DateTime $date       Le jour de la réservation
     * @param \DateTime $heure      L'heure de la réservation
     * @param int       $nbPersType Le nombre de personnes
     *
     * @return Table|null Un objet Table si une place est trouvée, sinon null
     */
    public function findAvailableTables(Restaurant $restaurant, \DateTime $date, \DateTime $heure, int $nbPersType): ?Table
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.reservations', 'r', 'WITH', 'r.date = :date AND r.heure = :heure')
            ->where('t.nbPlace >= :nbPers')
            ->andWhere('t.disponible = true')
            ->andWhere('t.restaurant = :restaurant')
            ->andWhere('r.id IS NULL')
            ->setParameter('restaurant', $restaurant)
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('heure', $heure->format('H:i:s'))
            ->setParameter('nbPers', $nbPersType);
        $query = $qb->getQuery();

        return $query->setMaxResults(1)->getOneOrNullResult();
    }
}
