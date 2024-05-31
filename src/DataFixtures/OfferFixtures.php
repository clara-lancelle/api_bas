<?php

namespace App\DataFixtures;

use App\Entity\JobProfile;
use App\Entity\Offer;
use App\Entity\Company;
use App\Entity\OfferMission;
use App\Entity\OfferRequiredProfile;
use App\Entity\Skill;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Factory\OfferFactory;
use App\Factory\OfferMissionFactory;
use App\Factory\OfferRequiredProfileFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OfferFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $firstCompany = $manager->getRepository(Company::class)->findOneBy([], ['id' => 'ASC']);
        $offer        = new Offer();
        $offer->setCompany($firstCompany);
        $offer->setName('Assistant Social Media');
        $offer->setStartDate(new \DateTime('2024-09-01'));
        $offer->setEndDate(new \DateTime('2025-08-31'));
        $offer->setType(OfferType::Apprenticeship);
        $offer->setStudylevel(StudyLevel::Level3);
        $offer->setDuration(Duration::between2and6months);
        $offer->setDescription('Mentalworks est à la recherche d\'un(e) assistant(e) en marketing des médias sociaux pour l\'aider à gérer ses réseaux en ligne. Vous serez responsable de la surveillance de nos canaux de médias sociaux, de la création de contenu, de la recherche de moyens efficaces d\'engager la communauté et d\'inciter les autres à s\'engager sur nos canaux.');
        $offer->setRevenue('Entre 1.000€ et 1.300€');
        $offer->setRemote('Télétravail 1 jour par semaine');
        $offer->setAvailablePlace(3);
        $offer->setApplicationLimitDate(new \DateTime('2024-08-01'));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Canva']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Réseaux sociaux']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Gestion de projet']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Marketing de contenu']));
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy(['name' => 'Design']));
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy(['name' => 'Marketing']));
        $offer->addMission((new OfferMission())->setText('Engagement de la communauté pour s\'assurer qu\'elle est soutenue et activement représentée en ligne'));
        $offer->addMission((new OfferMission())->setText('Se concentrer sur la publication de contenu sur les médias sociaux'));
        $offer->addMission((new OfferMission())->setText('Soutien au marketing et à la stratégie'));
        $offer->addMission((new OfferMission())->setText('Rester à l\'affût des tendances sur les plateformes de médias sociaux et suggérer des idées de contenu à l\'équipe.'));
        $offer->addMission((new OfferMission())->setText('Participer à des communautés en ligne'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Vous êtes passionné par le digital et pratiquez les principaux réseaux sociaux'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Vous avez le sens des relations humaines et du travail en équipe'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Vous avez un état d’esprit positif et  confiant et n’hésitez pas à apprendre de nouvelles compétences et à assumer des responsabilités supplémentaires'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Vous avez le sens du détail et de la créativité et maîtrisez Canva'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Vous avez déjà assuré des missions de Community Manager sur Instagram, Linkedin, Facebook, TikTok et X'));
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
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'React']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'JavaScript']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Gestion de projet']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Méthodologies Agile']));
        $offer->addMission((new OfferMission())->setText('Développement de nouvelles fonctionnalités pour nos applications web'));
        $offer->addMission((new OfferMission())->setText('Maintenance et amélioration du code existant'));
        $offer->addMission((new OfferMission())->setText('Participation aux revues de code et aux tests'));
        $offer->addMission((new OfferMission())->setText('Collaboration avec l\'équipe de conception pour créer des interfaces utilisateur intuitives'));
        $offer->addMission((new OfferMission())->setText('Rédaction de documentation technique'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Maîtrise des langages de programmation web tels que HTML, CSS, JavaScript et PHP'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Capacité à travailler en équipe et à communiquer efficacement'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Expérience avec les frameworks web modernes'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Compréhension des principes de l\'UI/UX design'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Bonnes compétences en résolution de problèmes et en pensée analytique'));
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
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'PHP']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'MySQL']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Gestion de projet']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Méthodologies Agile']));
        $offer->addSkill($manager->getRepository(Skill::class)->findOneBy(['name' => 'Symfony']));
        $offer->addMission((new OfferMission())->setText('Participation au développement de projets web innovants'));
        $offer->addMission((new OfferMission())->setText('Assurer la qualité du code via des tests et des revues de code'));
        $offer->addMission((new OfferMission())->setText('Collaborer avec des équipes multidisciplinaires pour atteindre les objectifs du projet'));
        $offer->addMission((new OfferMission())->setText('Contribuer à l\'optimisation et à la performance des applications web'));
        $offer->addMission((new OfferMission())->setText('Assister dans la résolution des problèmes techniques et des bugs'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Bonne connaissance des langages web tels que JavaScript, HTML, CSS, et PHP'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Capacité à travailler de manière autonome et en équipe'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Expérience avec des systèmes de gestion de version comme Git'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Connaissance des principes de développement agile'));
        $offer->addRequiredProfile((new OfferRequiredProfile())->setText('Capacité à résoudre des problèmes techniques complexes'));    
        $offer->addJobProfile($manager->getRepository(JobProfile::class)->findOneBy([], ['id' => 'DESC']));
        $manager->persist($offer);

        OfferFactory::createMany(10, [
            'missions' => OfferMissionFactory::new()->many(1, 5),
            'required_profiles' => OfferRequiredProfileFactory::new()->many(1, 5)
        ]);
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            SkillFixtures::class,
        ];
    }
}