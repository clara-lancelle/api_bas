<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName('MentalWorks');
        $company->setWebsiteUrl('https://mentalworks.fr/');
        $company->setSocialReason('MENTAL WORKS');
        $company->setSiret('39242018800048');
        $company->setAddress('41 Rue Irene Joliot Curie bât Millenium 2, 60610 La Croix-Saint-Ouen');
        $company->setPhoneNum('0344862255');
        $company->setDescription('Mentalworks est une agence web spécialisée dans la création de sites de sites web, d\'applis mobiles et le développement d\'applications métiers sur-mesure.');
        $company->setActivity('Concepteur de sites web');
        $manager->persist($company);
        $manager->flush();
    }
}