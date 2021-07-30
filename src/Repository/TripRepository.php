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
     * function to find available trips
     *
     * @param integer $limit
     * @return array
     */
    public function findAvailableTrips($limit = null): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.reserved = :reserved')
            ->andWhere('t.availableSeatNumber >= :seatNumber')
            ->andWhere('t.status = :status')
            ->setParameters(['reserved' => false, 'seatNumber' => 1, 'status' => 2])
            ->addOrderBy('t.departureAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Function to return all trips created by a user (useful to set automatically the trip name when user create a trip)
     *
     * @param string $user
     * @return array
     */
    public function findUserTrips($user): array
    {
        return $this
            ->createQueryBuilder('t')
            ->andWhere('t.reserved = :reserved')
            ->andWhere('t.name LIKE :user')
            ->setParameters(['user' => '%' . $user . '%', 'reserved' => true])
            ->getQuery()
            ->getResult();
    }

    /**
     * sort all standard trips to a specific order
     *
     * @param string $orderBy
     * @param string $order
     * @return array
     */
    public function orderTrips($orderBy, $order, $reserved = false): array
    {
        return $this
            ->createQueryBuilder('t')
            ->innerJoin('t.spacecraft', 's')
            ->andWhere('t.reserved = :reserved')
            ->setParameter('reserved', $reserved)
            ->addOrderBy($orderBy, $order)
            ->getQuery()
            ->getResult();
    }

    /**
     * Show all trips available of a specific destination
     *
     * @param Destination $destination
     * @param int $limit
     * @return array
     */
    public function AvailableTripByDestination($destination, $limit): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.reserved = :reserved')
            ->andWhere('t.availableSeatNumber >= :seatNumber')
            ->andWhere('t.destination = :destination')
            ->setParameters(['reserved' => false, 'seatNumber' => 1, 'destination' => $destination])
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Manage trip filter
     *
     * @param int $spacecraft
     * @param int $destination
     * @param int $price
     * @return array
     */
    public function sortTrips($spacecraft = null, $destination = null, $price = null): array
    {
        $response = $this->createQueryBuilder('t');

        if ($spacecraft !== null) {
            $response->orWhere('t.spacecraft = :spacecraft')
                ->setParameter('spacecraft', $spacecraft);
        }
        if ($destination !== null) {
            $response->andWhere('t.destination = :destination')
                ->setParameter('destination', $destination);
        }
        if ($price !== null) {
            $response->andWhere('t.price < :price')
                ->setParameter('price', $price);
        }
        return $response->getQuery()
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
