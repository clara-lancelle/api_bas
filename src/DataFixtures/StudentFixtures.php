<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\Formation;
use App\Enum\Gender;
use App\Entity\Hobbie;
use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class StudentFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $hasher, private EntityManagerInterface $entityManager)
    {
    }

    public function fakeUpload(File $file): string
    {
        $filesystem = new Filesystem();
        try {
            $filesystem->copy($file, __DIR__ . '/../../public/assets/images/users/' . $file->getFilename());
        } catch (FileException $e) {
            dd($e);
        }
        return $file;
    }

    public function load(ObjectManager $manager)
    {
        //Student
        $avatar = 'avatar.png';
        $user = new Student();
        $user->setName('student');
        $user->setFirstname('test');
        $user->setEmail('student@test.com');
        $password = $this->hasher->hashPassword($user, 'pass_student_1234');
        $user->setPassword($password);
        $user->setCellphone("0164025663");
        $user->setCity('paris');
        $user->setZipCode(75002);
        $user->setBirthdate(new \DateTime('2000-04-03'));
        $user->setRoles(['ROLE_USER']);
        $user->setGender(Gender::Male)
        ->setPersonnalWebsite('http://test.com')
        ->setHandicap(true)
        ->setDriverLicense(false)
        ->setLinkedinPage('http://linkedin.com/test');
        $this->fakeUpload(new File(__DIR__ . '/images/users/'. $avatar));
        $user->setProfileImage($avatar);
        $manager->persist($user);
        $manager->flush();


        //hobbie
        $hobbie = new Hobbie();
        $hobbie->setName('Football');
        $hobbie->setStartDate(new \DateTime('2010-04-03'));
        $hobbie->setStudent($user);
        $manager->persist($hobbie);
        $manager->flush();

        //experience
        $exp = new Experience();
        $exp->setCompanyName('Tesla');
        $exp->setPosition('developpeur');
        $exp->setDescription('lorem ipsum dolor sit..');
        $exp->setStartDate(new \DateTime('2020-04-03'));
        $exp->setEndDate(new \DateTime('2020-10-11'));
        $exp->setStudent($user);
        $manager->persist($exp);
        $manager->flush();

        //formation
        $form = new Formation();
        $form->setSchoolName('St Vincent');
        $form->setName('Licence informatique');
        $form->setLevel('Licence');
        $form->setStartDate(new \DateTime('2020-10-11'));
        $form->setStudent($user);
        $manager->persist($form);
        $manager->flush();

        $this->fakeUpload(new File(__DIR__ . '/images/users/usr.png'));
    }
}