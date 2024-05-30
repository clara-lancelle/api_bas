<?php

namespace App\Repository;

use App\Entity\JobProfile;
use App\Entity\Offer;
use App\Enum\OfferType;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

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

    public function getOffers(?OfferType $type = null, int $limit = 0): array
    {
        $offers = $this->createQueryBuilder('o')
            ->select('o.id', 'c.name as companyName', 'c.picto_image', 'o.name', 'o.type', 'o.description', 'o.application_limit_date', 'o.start_date', 'o.end_date', 'c.city')
            ->join('o.company', 'c')
            ->orderBy('o.created_at', 'ASC')
        ;
        $limit && $offers->setMaxResults($limit);
        $type && $offers->where(['o.type = :type'])->setParameter('type',$type);
        $offers = $offers->getQuery()->getResult();
      
        foreach ($offers as &$offer) {

            //date calcul & formats 
            $calcul_duration = $offer['start_date']->diff($offer['end_date']);
            $offer['calcul_duration'] = $calcul_duration->d;
            $calcul_limit_date = $offer['application_limit_date']->diff(new DateTime());
            $offer['calcul_limit_date'] = $calcul_limit_date->d;
            $offer['start_date'] = date_format($offer['start_date'], 'd/m/Y');
            $offer['end_date'] = date_format($offer['end_date'], 'd/m/Y');

            // add job_profiles
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
