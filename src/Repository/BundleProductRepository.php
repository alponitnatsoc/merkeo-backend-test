<?php

namespace App\Repository;

use App\Entity\BundleProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BundleProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BundleProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BundleProduct[]    findAll()
 * @method BundleProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BundleProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BundleProduct::class);
    }

//    /**
//     * @return BundleProduct[] Returns an array of BundleProduct objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BundleProduct
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
