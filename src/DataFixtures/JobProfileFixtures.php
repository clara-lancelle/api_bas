<?php

namespace App\DataFixtures;

use App\Entity\JobProfile;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class JobProfileFixtures extends Fixture
{
    public const REFERENCE = 'job_profile_';

    public static function data(): array
    {
        return [
            ['Design' => '#56CDAD'],
            ['Commercial' => '#FFA500'],
            ['Marketing' => '#EB8533'],
            ['Business' => '#8B4513'],
            ['Management' => '#4682B4'],
            ['Finance' => '#4640DE'],
            ['Industrie' => '#A52A2A'],
            ['Informatique' => '#FF6550'],
            ['Ressources humaines' => '#32CD32'],
            ['Juridique' => '#000080'],
            ['Communication' => '#FFD700'],
            ['Recherche  et développement' => '#ADFF2F'],
            ['Logistique' => '#D2691E'],
            ['Santé' => '#FF1493'],
            ['Éducation' => '#9370DB'],
            ['Environnement' => '#228B22'],
            ['Artisanat' => '#DAA520'],
            ['Services à la personne' => '#FF69B4'],
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::data() as $loop => $profileData) {
            foreach ($profileData as $name => $color) {
                $jobProfile = new JobProfile();
                $jobProfile->setName($name);
                $jobProfile->setColor($color);
                $manager->persist($jobProfile);
    
                $this->setReference(self::REFERENCE.$loop, $jobProfile);
            }
        }
        
        $manager->flush();
    }
}