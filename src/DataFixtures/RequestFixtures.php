<?php

namespace App\DataFixtures;

use App\Entity\Request;
use App\Entity\Student;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Factory\JobProfileFactory;
use App\Factory\RequestFactory;
use App\Factory\StudentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RequestFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {

      // Creating the first request
      RequestFactory::createOne([
        'name' => 'Développeur Web Junior',
        'school' => 'Université de Paris',
        'description' => 'Le métier de développeur web m\'a toujours beaucoup intéressé. '
            . 'Je suis passionné par la création de sites web et d\'applications interactives. '
            . 'Je cherche à approfondir mes connaissances en front-end et back-end à travers cette opportunité.',
        'study_level' => StudyLevel::Level3,
        'type' => OfferType::Apprenticeship,
        'duration' => Duration::between2and6months,
        'start_date' => new \DateTime('2024-09-01'),
        'end_date' => new \DateTime('2025-08-31'),
        'student' => StudentFactory::new(),
        'job_profiles' => JobProfileFactory::randomRange(1, 3)
    ]);

    // Creating the second request
    RequestFactory::createOne([
        'name' => 'Développeur Web',
        'school' => 'École Polytechnique',
        'description' => 'Stage de plusieurs mois au sein de l\'équipe de développement. '
            . 'Je suis motivé pour améliorer mes compétences techniques et travailler sur des projets innovants. '
            . 'Mon objectif est de devenir un développeur full-stack compétent.',
        'study_level' => StudyLevel::Level1,
        'type' => OfferType::Internship,
        'duration' => Duration::between6and12months,
        'start_date' => new \DateTime('2024-09-01'),
        'end_date' => new \DateTime('2025-08-31'),
        'student' => StudentFactory::new(),
        'job_profiles' => JobProfileFactory::randomRange(1, 3)
    ]);

    // Creating the third request
    RequestFactory::createOne([
        'name' => 'Développeur Web',
        'school' => 'Université de Bordeaux',
        'description' => 'Stage de plusieurs mois au sein de l\'équipe de développement. '
            . 'Je suis particulièrement intéressé par le développement de solutions web efficaces et évolutives. '
            . 'Je souhaite contribuer à des projets concrets et développer mes compétences en programmation.',
        'study_level' => StudyLevel::Level4,
        'type' => OfferType::Internship,
        'duration' => Duration::between6and12months,
        'start_date' => new \DateTime('2024-11-01'),
        'end_date' => new \DateTime('2025-12-31'),
        'student' => StudentFactory::new(),
        'job_profiles' => JobProfileFactory::randomRange(1, 3)
    ]);

    // Creating 10 random requests using the factory
    RequestFactory::createMany(10);

    $manager->flush();
    }
    public function getDependencies()
    {
        return [
            StudentFixtures::class,
            JobProfileFixtures::class
        ];
    }

}