<?php

namespace App\DataFixtures;

use App\Entity\JobProfile;
use App\Entity\Offer;
use App\Entity\Company;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Factory\JobProfileFactory;
use App\Factory\OfferFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $firstCompany = $manager->getRepository(Company::class)->findOneBy([], ['id' => 'ASC']);
        $offer        = new Offer();
        $offer->setCompany($firstCompany);
        $offer->setName('Développeur Web Junior');
        $offer->setStartDate(new \DateTime('2024-09-01'));
        $offer->setEndDate(new \DateTime('2025-08-31'));
        $offer->setType(OfferType::Apprenticeship);
        $offer->setStudylevel(StudyLevel::Level3);
        $offer->setDuration(Duration::between2and6months);
        $offer->setDescription('Alternance de plusieurs mois a sein de l\'équipe de développement.');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-10-01'));
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'ASC']));
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'DESC']));
        $manager->persist($offer);
        $manager->flush();

        $firstCompany = $manager->getRepository(Company::class)->findOneBy([], ['id' => 'ASC']);
        $offer        = new Offer();
        $offer->setCompany($firstCompany);
        $offer->setName('Développeur Web');
        $offer->setStartDate(new \DateTime('2024-09-01'));
        $offer->setEndDate(new \DateTime('2025-08-31'));
        $offer->setType(OfferType::Internship);
        $offer->setStudylevel(StudyLevel::Level1);
        $offer->setDuration(Duration::between6and12months);
        $offer->setDescription('Stage de plusieurs mois a sein de l\'équipe de développement.');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-11-01'));
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'DESC']));
        $manager->persist($offer);

        $lastCompany = $manager->getRepository(Company::class)->findOneBy([], ['id' => 'DESC']);
        $offer       = new Offer();
        $offer->setCompany($lastCompany);
        $offer->setName('Développeur Web');
        $offer->setStartDate(new \DateTime('2024-11-01'));
        $offer->setEndDate(new \DateTime('2025-12-31'));
        $offer->setType(OfferType::Internship);
        $offer->setStudylevel(StudyLevel::Level4);
        $offer->setDuration(Duration::between6and12months);
        $offer->setDescription('Stage de plusieurs mois a sein de l\'équipe de développement.');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-08-01'));
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'DESC']));
        $manager->persist($offer);

        $manager->flush();

        OfferFactory::createMany(10, ['job_profiles' => JobProfileFactory::new()->many(0, 3)]);
    }
}