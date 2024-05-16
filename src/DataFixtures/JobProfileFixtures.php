<?php

namespace App\DataFixtures;

use App\Entity\JobProfile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobProfileFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $jobProfiles = [
            'Design',
            'Commercial',
            'Marketing',
            'Business',
            'Management',
            'Finance',
            'Industrie',
            'Informatique',
            'Ressources humaines',
            'Juridique',
            'Communication',
            'Recherche et développement',
            'Logistique',
            'Santé',
            'Éducation',
            'Environnement',
            'Artisanat',
            'Services à la personne'
        ];
        foreach ($jobProfiles as $profileName) {
            $jobProfile = new JobProfile();
            $jobProfile->setName($profileName);
            $manager->persist($jobProfile);
        }
        $manager->flush();
    }
}