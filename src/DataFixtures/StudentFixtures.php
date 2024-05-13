<?php

namespace App\DataFixtures;

use App\Entity\Administrator;
use App\Entity\Company;
use App\Entity\CompanyUser;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Entity\Hobbie;
use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\User;
use App\Enum\Gender;
use App\Enum\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StudentFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher, private EntityManagerInterface $entityManager)
    {
    }

    public function load(ObjectManager $manager)
    {
        //Student
        $user = new Student();
        $user->setName('student');
        $user->setFirstname('test');
        $user->setEmail('student@test.com');
        $password = $this->hasher->hashPassword($user, 'pass_student_1234');
        $user->setPassword($password);
        $user->setCellphone("0164025663");
        $user->setCity('paris');
        $user->setZipCode(75002);
        $user->setBirthdate(new \DateTime('2000-04-03'));
        $user->setRoles(['ROLE_USER']);
        $user->setGender(Gender::MALE);
        $manager->persist($user);
        $manager->flush();


        //hobbie
        $hobbie = new Hobbie();
        $hobbie->setName('Football');
        $hobbie->setStartDate(new \DateTime('2010-04-03'));
        $hobbie->setStudent($user);
        $manager->persist($hobbie);
        $manager->flush();

        //experience
        $exp = new Experience();
        $exp->setCompanyName('Tesla');
        $exp->setPosition('developpeur');
        $exp->setDescription('lorem ipsum dolor sit..');
        $exp->setStartDate(new \DateTime('2020-04-03'));
        $exp->setEndDate(new \DateTime('2020-10-11'));
        $exp->setStudent($user);
        $manager->persist($exp);
        $manager->flush();

        //skill
        $skill = new Skill();
        $skill->setName('PHP');
        $skill->setStartDate(new \DateTime('2020-10-11'));
        $skill->setStudent($user);
        $manager->persist($skill);
        $manager->flush();

        //formation
        $form = new Formation();
        $form->setSchoolName('St Vincent');
        $form->setName('Licence informatique');
        $form->setLevel('Licence');
        $form->setStartDate(new \DateTime('2020-10-11'));
        $form->setStudent($user);
        $manager->persist($form);
        $manager->flush();
    }
}