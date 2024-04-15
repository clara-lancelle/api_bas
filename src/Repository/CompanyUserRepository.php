<?php

namespace App\Repository;

use App\Entity\CompanyUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanyUser>
 *
 * @method CompanyUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyUser[]    findAll()
 * @method CompanyUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyUser::class);
    }

    //    /**
    //     * @return CompanyUser[] Returns an array of CompanyUser objects
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

    //    public function findOneBySomeField($value): ?CompanyUser
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
