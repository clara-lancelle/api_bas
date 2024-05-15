<?php

namespace App\DataFixtures;

use App\Entity\Administrator;
use App\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdministratorFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher, private EntityManagerInterface $entityManager)
    {
    }

    public function load(ObjectManager $manager)
    {
        //Administrator
        $user = new Administrator();
        $user->setName('admin');
        $user->setFirstname('admin');
        $user->setEmail('admin@test.com');
        $password = $this->hasher->hashPassword($user, 'pass_1234');
        $user->setPassword($password);
        $user->setCellphone("0164025663");
        $user->setCity('paris');
        $user->setZipCode(75002);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setGender(Gender::MALE);
        $manager->persist($user);
        $manager->flush();
    }
}