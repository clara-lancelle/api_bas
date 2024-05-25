<?php

namespace App\DataFixtures;

use App\Entity\JobProfile;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class JobProfileFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $jobProfiles = [
            ['Design', '#56CDAD'],
            ['Commercial', '#FFA500'],
            ['Marketing', '#EB8533'],
            ['Business', '#8B4513'],
            ['Management', '#4682B4'],
            ['Finance', '#4640DE'],
            ['Industrie', '#A52A2A'],
            ['Informatique', '#FF6550'],
            ['Ressources humaines', '#32CD32'],
            ['Juridique', '#000080'],
            ['Communication', '#FFD700'],
            ['Recherche et développement', '#ADFF2F'],
            ['Logistique', '#D2691E'],
            ['Santé', '#FF1493'],
            ['Éducation', '#9370DB'],
            ['Environnement', '#228B22'],
            ['Artisanat', '#DAA520'],
            ['Services à la personne', '#FF69B4']
        ];
        foreach ($jobProfiles as $profile) {
            $jobProfile = new JobProfile();
            $jobProfile->setName($profile[0]);
            $jobProfile->setColor($profile[1]);
            $manager->persist($jobProfile);
        }
        $manager->flush();
    }
}