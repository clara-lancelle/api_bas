<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('admin');
        $user->setFirstname('admin');
        $user->setEmail('admin@test.com');
        $password = $this->hasher->hashPassword($user, 'pass_1234');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}