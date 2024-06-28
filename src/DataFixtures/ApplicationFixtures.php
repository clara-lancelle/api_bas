<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Experience;
use App\Enum\ExperienceType;
use App\Factory\OfferFactory;
use App\Factory\StudentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApplicationFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
       
        $app = new Application();
        $exp = new Experience();
        $exp
            ->setCompany('Orange')
            ->setType(ExperienceType::Apprenticeship)
            ->setYear('2021')
            ;
        $manager->persist($exp);
        
        $app
            ->setStudent(StudentFactory::createOne()->object())
            ->setMotivations('')
            ->setSchoolName('ESGI')
            ->setOffer(OfferFactory::createOne()->object())
            ->setCoverLetter('Lettre-de-motivation.pdf')
            ->addExperience($exp)
        ;
        for($i = 0; $i <= 3; $i++) {
            $app->addSkill($this->getReference(SkillFixtures::REFERENCE.rand(0,count(SkillFixtures::data())))); 
        }
        $manager->persist($app);
        $manager->flush();
    }

     public function getDependencies(): array
    {
        return [
            StudentFixtures::class,
            OfferFixtures::class
        ];
    }
}