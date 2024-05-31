<?php

namespace App\DataFixtures;

use App\Entity\CompanyCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyCategoryFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $categories = [
           'Services aux particuliers', 
           'Services aux entreprises',
           'Mairie, collectivité',
           'Association, ONG',
            'Organismes d’état',
        ];
        foreach ($categories as $category) {
            $jobProfile = new CompanyCategory();
            $jobProfile->setName($category);
            $manager->persist($jobProfile);
        }
        $manager->flush();
    }
}