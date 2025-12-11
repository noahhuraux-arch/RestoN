<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    //    /**
    //     * @return Client[] Returns an array of Client objects
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

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findExistingByClient(string $nom, string $prenom, string $email, string $telephone)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.nom = :nom')
            ->andWhere('c.prenom = :prenom')
            ->andWhere('c.email = :email')
            ->andWhere('c.telephone = :telephone')
            ->setParameter('nom', $nom)
            ->setParameter('prenom', $prenom)
            ->setParameter('email', $email)
            ->setParameter('telephone', $telephone);
        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}
