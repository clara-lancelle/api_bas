<?php

namespace App\DataFixtures;

use App\Entity\JobProfile;
use App\Entity\Offer;
use App\Entity\Company;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Factory\OfferFactory;
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
        $offer        = new Offer();
        $offer->setCompany($firstCompany);
        $offer->setName('Développeur Web Junior');
        $offer->setStartDate(new \DateTime('2024-09-01'));
        $offer->setEndDate(new \DateTime('2025-08-31'));
        $offer->setType(OfferType::Apprenticeship);
        $offer->setStudylevel(StudyLevel::Level3);
        $offer->setDuration(Duration::between2and6months);
        $offer->setDescription('Alternance de plusieurs mois a sein de l\'équipe de développement.');
        $offer->setPromoteStatus('Bac+2');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-01-01'));
        $offer->setJobProfile($this->entityManager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'ASC']));
        $manager->persist($offer);
        $manager->flush();

        $firstCompany = $this->entityManager->getRepository(Company::class)->findOneBy([], ['id' => 'ASC']);
        $offer        = new Offer();
        $offer->setCompany($firstCompany);
        $offer->setName('Développeur Web');
        $offer->setStartDate(new \DateTime('2024-09-01'));
        $offer->setEndDate(new \DateTime('2025-08-31'));
        $offer->setType(OfferType::Internship);
        $offer->setStudylevel(StudyLevel::Level1);
        $offer->setDuration(Duration::between6and12months);
        $offer->setDescription('Stage de plusieurs mois a sein de l\'équipe de développement.');
        $offer->setPromoteStatus('Bac+2');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-01-01'));
        $offer->setJobProfile($this->entityManager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'DESC']));
        $manager->persist($offer);

        $lastCompany = $this->entityManager->getRepository(Company::class)->findOneBy([], ['id' => 'DESC']);
        $offer       = new Offer();
        $offer->setCompany($lastCompany);
        $offer->setName('Développeur Web');
        $offer->setStartDate(new \DateTime('2024-11-01'));
        $offer->setEndDate(new \DateTime('2025-12-31'));
        $offer->setType(OfferType::Internship);
        $offer->setStudylevel(StudyLevel::Level4);
        $offer->setDuration(Duration::between6and12months);
        $offer->setDescription('Stage de plusieurs mois a sein de l\'équipe de développement.');
        $offer->setPromoteStatus('Bac+2');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-08-01'));
        $offer->setJobProfile($this->entityManager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'DESC']));
        $manager->persist($offer);

        $manager->flush();

        OfferFactory::createMany(10);
    }
}