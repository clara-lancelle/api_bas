<?php

namespace App\Repository;

use App\Entity\JobProfile;
use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    public function getLastOffers($limit = 8): array
    {
        $offers = $this->createQueryBuilder('o')
            ->select('o.id', 'c.name as companyName', 'c.picto_image', 'o.name', 'o.type', 'o.description', 'c.city')
            ->join('o.company', 'c')
            ->orderBy('o.created_at', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
        foreach ($offers as &$offer) {
            $offerEntity = $this->findOneBy(['id' => $offer['id']]);
            $offer['job_profiles'] = $offerEntity->getJobProfiles()->map(function ($jp) {
                return [
                    'name' => $jp->getName(),
                    'color' => $jp->getColor()
                ];
            })->toArray();
        }
        return $offers;
    }
}
