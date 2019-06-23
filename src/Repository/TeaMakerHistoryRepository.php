<?php

namespace App\Repository;

use App\Entity\TeaMakerHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeaMakerHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeaMakerHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeaMakerHistory[]    findAll()
 * @method TeaMakerHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeaMakerHistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeaMakerHistory::class);
    }

    // /**
    //  * @return TeaMakerHistory[] Returns an array of TeaMakerHistory objects
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
    public function findOneBySomeField($value): ?TeaMakerHistory
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
