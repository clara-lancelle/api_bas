<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

class CompanyFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
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
        $company->setActivity('Concepteur de sites web');
        $this->fakeUpload(new File(__DIR__ . '/images/companies/' . $largeImage));
        $company->setLargeImage($largeImage);
        $this->fakeUpload(new File(__DIR__ . '/images/companies/' . $picto));
        $company->setPictoImage($picto);

        $manager->persist($company);
        $manager->flush();
    }
}