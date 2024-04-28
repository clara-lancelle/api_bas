<?php

namespace App\DataFixtures;
use App\Entity\Offer;
use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends Fixture
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager)
    {
        $firstCompany = $this->entityManager->getRepository(Company::class)->findOneBy([], ['id' => 'ASC']);

        $offer = new Offer();
        $offer->setCompany($firstCompany);
        $offer->setName('Développeur Web Junior');
        $offer->setStartDate(new \DateTime('2024-09-01')); 
        $offer->setEndDate(new \DateTime('2025-08-31'));   
        $offer->setType('alternance');                
        $offer->setDescription('Alternance de plusieurs mois a sein de l\'équipe de développement.'); 
        $offer->setPromoteStatus('Bac+2');                    
        $offer->setRevenue('Entre 1.000€ et 1.300€');                          
        $offer->setRemote('Télétravail 1 jour par semaine');                           
        $offer->setAvailablePlace(3);
        $manager->persist($offer);
        $manager->flush();
    }
}