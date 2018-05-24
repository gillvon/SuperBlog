<?php

namespace App\Repository;

use App\Entity\Viewer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Viewer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Viewer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Viewer[]    findAll()
 * @method Viewer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Viewer::class);
    }

//    /**
//     * @return Viewer[] Returns an array of Viewer objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Viewer
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
