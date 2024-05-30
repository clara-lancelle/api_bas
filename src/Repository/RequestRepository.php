<?php

namespace App\Repository;

use App\Entity\Request;
use DateTime;
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
            ->select('r.id', 'r.name', 'r.description', 'r.start_date', 'r.end_date', 'r.type', 's.firstname', 's.birthdate', 's.city', 's.profile_image')
            ->join('r.student', 's')
            ->orderBy('r.created_at', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        foreach ($requests as &$request) {
            
            // date calcul & formats
            $calcul_duration = $request['start_date']->diff($request['end_date']);
            $calcul_age = $request['birthdate']->diff(new DateTime());
            $request['calcul_duration'] = $calcul_duration->d;
            $request['calcul_age'] = $calcul_age->y;
            $request['start_date'] = date_format($request['start_date'], 'd/m/Y');
            $request['end_date'] = date_format($request['end_date'], 'd/m/Y');

             // add job_profiles
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
