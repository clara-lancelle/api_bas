<?php

namespace App\DataFixtures;

use App\Entity\SocialNetwork;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SocialNetworkFixtures extends Fixture
{
    public const REFERENCE = 'social_network_';

    public static function data(): array
    {
        return [
            ['Linkedin' => 'ln.png'],
            ['Twitter' => 'tweeter.png'],
            ['Facebook' => 'fb.png'],
            ['Instagram' => 'insta.png'],
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::data() as $i => $item) {
            foreach ($item as $name => $image) {
                $socialNetwork = new SocialNetwork();
                $socialNetwork->setName($name);
                $socialNetwork->setLogo($image);
                $manager->persist($socialNetwork);
    
                $this->setReference(self::REFERENCE.$i, $socialNetwork);
            }
        }
        
        $manager->flush();
    }
}