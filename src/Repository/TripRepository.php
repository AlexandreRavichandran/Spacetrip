<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }


    /**
     * Show all available trips (trips that are not reserved and where available seats are more than 1)
     *
     * @return array
     */
    public function findAvailableTrips(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.reserved = :reserved')
            ->andWhere('t.availableSeatNumber >= :seatNumber')
            ->setParameters(['reserved' => false, 'seatNumber' => 1])
            ->getQuery()
            ->getResult();
    }

    /**
     * Function to return all trips created by a user (useful to set automatically the trip name when user create a trip)
 
     * @return array
     */
    public function findNumberOfTrips($user): array
    {
        return $this
            ->createQueryBuilder('t')
            ->andWhere('t.name LIKE :user')
            ->setParameter('user', '%' . $user . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Function to sort trips with an order
     * @return array
     */
    public function orderTrips($orderBy, $order): array
    {
        return $this
            ->createQueryBuilder('t')
            ->innerJoin('t.spacecraft', 's')
            ->addOrderBy($orderBy, $order)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Trip[] Returns an array of Trip objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trip
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
