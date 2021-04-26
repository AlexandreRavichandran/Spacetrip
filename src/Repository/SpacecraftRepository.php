<?php

namespace App\Repository;

use App\Entity\Spacecraft;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spacecraft|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spacecraft|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spacecraft[]    findAll()
 * @method Spacecraft[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpacecraftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spacecraft::class);
    }

    /**
     * Select the latest created spacetrips
     * @return array
     */
    public function findLatestSpacecrafts($field, $limit = null): array
    {
        return $this
            ->createQueryBuilder('t')
            ->orderBy('t.' . $field, 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }




    // /**
    //  * @return Spacecraft[] Returns an array of Spacecraft objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Spacecraft
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
