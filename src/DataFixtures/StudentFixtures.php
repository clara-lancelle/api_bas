<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\Language;
use App\Enum\Gender;
use App\Entity\Student;
use App\Enum\ExperienceType;
use App\Enum\LanguageLevel;
use App\Enum\LanguageName;
use App\Enum\StudyLevel;
use App\Enum\StudyYears;
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
        $password = $this->hasher->hashPassword($user, 'pass_student_1234');
        $user
            ->setName('student')
            ->setFirstname('test')
            ->setEmail('student@test.com')
            ->setPassword($password)
            ->setCellphone("0164025663")
            ->setCity('paris')
            ->setZipCode(75002)
            ->setBirthdate(new \DateTime('2000-04-03'))
            ->setRoles(['ROLE_USER'])
            ->setGender(Gender::Male)
            ->setPersonnalWebsite('http://test.com')
            ->setHandicap(true)
            ->setDriverLicense(false)
            ->setLinkedinPage('http://linkedin.com/test')
            ->setSchoolName('ESGI')
            ->setPreparedDegree(StudyLevel::Level1)
            ->setStudyYears(StudyYears::bac1)
            ;
        $this->fakeUpload(new File(__DIR__ . '/images/users/'. $avatar));
        $user->setProfileImage($avatar);
        $manager->persist($user);
        $manager->flush();

        //Languages
        $lang = new Language();
        $lang
            ->setName(LanguageName::French)
            ->setLevel(LanguageLevel::A1)
        ;

        //experience
        $exp = new Experience();
        $exp
            ->setCompany('Tesla')
            ->setType(ExperienceType::Internship)
            ->setYear('2023')
        ;

        $exp->setStudent($user);
        $manager->persist($exp);
        $manager->flush();

        $this->fakeUpload(new File(__DIR__ . '/images/users/usr.png'));
    }
}