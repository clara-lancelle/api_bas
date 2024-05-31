<?php

namespace App\DataFixtures;

use App\Entity\CompanyActivity;
use App\Entity\JobProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyActivityFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $activities = [
           'Marketing', 
           'Industrie mécanique',
           'Industrie chimique',
           'Informatique',
            'Automobile',
            'Tourisme, sport',
            'Finances',
            'Loisirs',
            'Alimentation',
            'Santé, bien-être',
            'Immobilier, BTP',
            'Média'
        ];
        foreach ($activities as $activity) {
            $jobProfile = new CompanyActivity();
            $jobProfile->setName($activity);
            $manager->persist($jobProfile);
        }
        $manager->flush();
    }
}