<?php

namespace App\DataFixtures;

use App\Factory\JobProfileFactory;
use App\Factory\RequestFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        RequestFactory::createMany(10,  ['job_profiles' => JobProfileFactory::new()->many(0, 3)]);
    }

}