<?php

namespace App\Repository;

use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @extends ServiceEntityRepository<Request>
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function getLastRequests($limit = 8): array
    {
        $requests = $this->createQueryBuilder('r')
            ->select('r.id', 'r.name', 'r.description', 'r.start_date', 'r.end_date', 'r.type', 's.name as companyName', 's.city', 's.profile_image')
            ->join('r.student', 's')
            ->orderBy('r.created_at', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        foreach ($requests as &$request) {
            $requestEntity = $this->find($request['id']);
            $request['job_profiles'] = $requestEntity->getJobProfiles()->map(function ($jp) {
                return [
                    'name' => $jp->getName(),
                    'color' => $jp->getColor()
                ];
            })->toArray();
        }
        return $requests;
    }

//    /**
//     * @return Request[] Returns an array of Request objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Request
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
