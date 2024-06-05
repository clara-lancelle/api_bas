<?php

namespace App\DataFixtures;

use App\Entity\CompanyCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyCategoryFixtures extends Fixture
{
    public const REFERENCE = 'category_';

    public function load(ObjectManager $manager)
    {
        $categories = [
           'Services aux particuliers', 
           'Services aux entreprises',
           'Mairie, collectivité',
           'Association, ONG',
            'Organismes d’état',
        ];
        foreach ($categories as $i => $item) {
            $category = new CompanyCategory();
            $category->setName($item);
            $manager->persist($category);

            $this->addReference(self::REFERENCE.$i, $category);
        }
        $manager->flush();
    }
}