<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Factory\CompanyFactory;
use App\Factory\CompanyImageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        CompanyImageFactory::createMany(7);
        $manager->flush();

        $largeImage = 'large_img_mw.png';
        $picto      = 'picto_mw.png';
        $company    = new Company();
        $company
            ->setName('MentalWorks')
            ->setWebsiteUrl('https://mentalworks.fr/')
            ->setSocialReason('SARL')
            ->setSiret('39242018800048')
            ->setAddress('41 Rue Irene Joliot Curie')
            ->setAdditionalAddress('bât Millenium 2')
            ->setWorkforce('22')
            ->setZipCode('60610')
            ->setCity('La Croix-Saint-Ouen')
            ->setPhoneNum('0344862255')
            ->setDescription('Mentalworks est une agence web spécialisée dans la création de sites de sites web, d\'applis mobiles et le développement d\'applications métiers sur-mesure.')
            ->setActivity($this->getReference(CompanyActivityFixtures::REFERENCE.rand(0, 11)))
            ->setCategory($this->getReference(CompanyCategoryFixtures::REFERENCE.rand(0, 4)))
            ->setPictoImage($picto)
            ->setLargeImage($largeImage)
        ;
        $manager->persist($company);
        for($i = 1; $i <= 5; $i++) {
            $company->addCompanyImage(CompanyImageFactory::createOne()->object());        
        }
        
        $manager->flush();


        $db      = 'dropbox.png';
        $company = new Company();
        $company
            ->setName('Dropbox France')
            ->setWebsiteUrl('https://www.dropbox.com/')
            ->setSocialReason('SARL')
            ->setSiret('78996481100022')
            ->setAddress('5 Avenue du Général de Gaulle')
            ->setAdditionalAddress('Batiment 8a')
            ->setZipCode('94160')
            ->setCity('Saint-Mandé')
            ->setWorkforce('890')
            ->setPhoneNum('0176541020')
            ->setDescription('Dropbox est un service d\'hébergement de fichiers qui offre le stockage en nuage, la synchronisation de fichiers, le cloud personnel et des logiciels clients.')
            ->setActivity($this->getReference(CompanyActivityFixtures::REFERENCE.rand(0, 11)))
            ->setCategory($this->getReference(CompanyCategoryFixtures::REFERENCE.rand(0, 4)))
            ->setLargeImage($db)
            ->setPictoImage($db)
        ;
        $manager->persist($company);
        for($i = 1; $i <= 5; $i++) {
            $company->addCompanyImage(CompanyImageFactory::createOne()->object());           
        }
        $manager->flush();

        $canva   = 'canva.png';
        $company = new Company;
        $company
            ->setName('Canva France')
            ->setWebsiteUrl('https://www.canva.com/fr_fr/')
            ->setSocialReason('SAS')
            ->setSiret('84331547800023') // Exemple de numéro SIRET pour Canva France
            ->setAddress('34 Avenue des Champs-Élysées')
            ->setAdditionalAddress('Etage 2')
            ->setZipCode('75008')
            ->setWorkforce('1800')
            ->setCity('Paris')
            ->setPhoneNum('0187654321') // Numéro de contact général de Canva France (exemple)
            ->setDescription('Canva est une plateforme de conception graphique en ligne qui permet de créer des graphiques, des présentations, des affiches et d\'autres contenus visuels.')
            ->setActivity($this->getReference(CompanyActivityFixtures::REFERENCE.rand(0, 11)))
            ->setCategory($this->getReference(CompanyCategoryFixtures::REFERENCE.rand(0, 4)))
            ->setLargeImage($canva)
            ->setPictoImage($canva);

        $manager->persist($company);    
        for($i = 1; $i <= 5; $i++) {
            $company->addCompanyImage(CompanyImageFactory::createOne()->object());          
        }
        $manager->flush();
        CompanyFactory::createMany(10, fn () => ['companyImages' => CompanyImageFactory::createMany(rand(5, 5))]);
    }

    public function getDependencies(): array
    {
        return [
            CompanyActivityFixtures::class,
            CompanyCategoryFixtures::class
        ];
    }
}