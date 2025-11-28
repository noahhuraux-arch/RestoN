<?php

namespace App\Repository;

use App\Entity\Reservation;
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
     * Trouve UNE table disponible pour un créneau donné
     *
     * @param \DateTime $date Le jour de la réservation
     * @param \DateTime $heure L'heure de la réservation
     * @param int $nbPersType Le nombre de personnes
     *
     * @return Table|null Un objet Table si une place est trouvée, sinon null
     */
    public function findAvailableTables(\DateTime $date, \DateTime $heure, int $nbPersType): ?Table
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT t
            FROM App\Entity\Table t
            WHERE t.disponible = true
            AND t.nbPlace >= :nbPers
            AND t.idTable NOT IN (
                SELECT r.table.idTable
                FROM App\Entity\Reservation r
                WHERE r.date = :date AND r.heure = :heure
            )"
        );
        $query->setParameter("date", $date);
        $query->setParameter("heure", $heure);
        $query->setParameter("nbPers", $nbPersType);
        $query->setMaxResults(1);
        return $query->getOneOrNullResult();



    }

}
