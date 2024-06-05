<?php

namespace App\DataFixtures;

use App\Entity\CompanyActivity;
use App\Entity\JobProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyActivityFixtures extends Fixture
{
    public const REFERENCE = 'activity_';

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
        foreach ($activities as $i => $item) {
            $activity = new CompanyActivity();
            $activity->setName($item);
            $manager->persist($activity);

            $this->addReference(self::REFERENCE.$i, $activity);
        }
        $manager->flush();
    }
}