<?php

namespace App\DataFixtures;

use App\Entity\Administrator;
use App\Entity\Company;
use App\Entity\CompanyUser;
use App\Entity\Student;
use App\Entity\User;
use App\Enum\Gender;
use App\Enum\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompanyUserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher, private EntityManagerInterface $entityManager)
    {
    }

    public function load(ObjectManager $manager)
    {
        $firstCompany = $this->entityManager->getRepository(Company::class)->findOneBy([], ['id' => 'ASC']);

        //CompanyUser
        $user = new CompanyUser();
        $user->setName('companyUser');
        $user->setFirstname('test');
        $user->setEmail('companyUser@test.com');
        $password = $this->hasher->hashPassword($user, 'pass_companyUser_1234');
        $user->setPassword($password);
        $user->setCellphone("0164025663");
        $user->setCity('paris');
        $user->setZipCode(75002);
        $user->setPosition('CTO');
        $user->setRoles(['ROLE_USER']);
        $user->setGender(Gender::MALE);
        $user->setCompany($firstCompany);
        $manager->persist($user);
        $manager->flush();
    }
}