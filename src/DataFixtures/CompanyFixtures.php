<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\CompanyActivity;
use App\Factory\CompanyFactory;
use App\Repository\CompanyActivityRepository;
use App\Repository\CompanyCategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

class CompanyFixtures extends Fixture
{
    public function __construct(private CompanyActivityRepository $companyActivityRepository, private CompanyCategoryRepository $companyCategoryRepository)
    {
    }

    public function fakeUpload(File $file): string
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->copy($file, __DIR__ . '/../../public/assets/images/companies/' . $file->getFilename());
        } catch (FileException $e) {
            dd($e);
        }
        return $file;
    }

    public function load(ObjectManager $manager)
    {

        $largeImage = 'large_img_mw.png';
        $picto      = 'picto_mw.png';
        $company    = new Company();
        $company->setName('MentalWorks');
        $company->setWebsiteUrl('https://mentalworks.fr/');
        $company->setSocialReason('SARL');  
        $company->setSiret('39242018800048');
        $company->setAddress('41 Rue Irene Joliot Curie bât Millenium 2');
        $company->setZipCode('60610');
        $company->setCity('La Croix-Saint-Ouen');
        $company->setPhoneNum('0344862255');
        $company->setDescription('Mentalworks est une agence web spécialisée dans la création de sites de sites web, d\'applis mobiles et le développement d\'applications métiers sur-mesure.');
        $company->setActivity($this->companyActivityRepository->findOneBy([], ['id'=>'asc']));
        $company->setCategory($this->companyCategoryRepository->findOneBy([], ['id'=>'asc']));
        $this->fakeUpload(new File(__DIR__ . '/images/companies/' . $largeImage));
        $company->setLargeImage($largeImage);
        $this->fakeUpload(new File(__DIR__ . '/images/companies/' . $picto));
        $company->setPictoImage($picto);

        $manager->persist($company);

        $db      = 'dropbox.png';
        $company = new Company();
        $company->setName('Dropbox France');
        $company->setWebsiteUrl('https://www.dropbox.com/');
        $company->setSocialReason('SARL');
        $company->setSiret('78996481100022');
        $company->setAddress('5 Avenue du Général de Gaulle');
        $company->setZipCode('94160');
        $company->setCity('Saint-Mandé');
        $company->setPhoneNum('0176541020');
        $company->setDescription('Dropbox est un service d\'hébergement de fichiers qui offre le stockage en nuage, la synchronisation de fichiers, le cloud personnel et des logiciels clients.');
        $company->setActivity($this->companyActivityRepository->findOneBy([], ['id'=>'desc']));
        $company->setCategory($this->companyCategoryRepository->findOneBy([], ['id'=>'desc']));
        $this->fakeUpload(new File(__DIR__ . '/images/companies/' . $db));
        $company->setLargeImage($db);
        $company->setPictoImage($db);

        $manager->persist($company);

        $canva   = 'canva.png';
        $company = new Company();
        $company->setName('Canva France');
        $company->setWebsiteUrl('https://www.canva.com/fr_fr/');
        $company->setSocialReason('SAS');
        $company->setSiret('84331547800023'); // Exemple de numéro SIRET pour Canva France
        $company->setAddress('34 Avenue des Champs-Élysées');
        $company->setZipCode('75008');
        $company->setCity('Paris');
        $company->setPhoneNum('0187654321'); // Numéro de contact général de Canva France (exemple)
        $company->setDescription('Canva est une plateforme de conception graphique en ligne qui permet de créer des graphiques, des présentations, des affiches et d\'autres contenus visuels.');
        $company->setActivity($this->companyActivityRepository->findOneBy([], ['id'=>'asc']));
        $company->setCategory($this->companyCategoryRepository->findOneBy([], ['id'=>'desc']));
        $this->fakeUpload(new File(__DIR__ . '/images/companies/' . $canva));
        $company->setLargeImage($canva);
        $company->setPictoImage($canva);

        $manager->flush();

        $this->fakeUpload(new File(__DIR__ . '/images/companies/img.png'));
        CompanyFactory::createMany(10);
    }
    public function getDependencies()
    {
        return [
            CompanyActivityFixtures::class,
            CompanyCategoryFixtures::class,
        ];
    }
}