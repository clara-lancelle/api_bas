<?php

namespace App\Controller;

use App\Entity\Application;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Company;
use App\Entity\CompanyActivity;
use App\Entity\CompanyCategory;
use App\Entity\CompanyUser;
use App\Entity\Experience;
use App\Entity\Offer;
use App\Entity\Skill;
use App\Entity\Student;
use App\Enum\ExperienceType;
use App\Enum\Gender;
use App\Enum\StudyLevel;
use App\Enum\StudyYears;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class PersistingApplication
{
    public function __construct(private EntityManagerInterface $manager, private UserPasswordHasherInterface $hasher, private ValidatorInterface $validator)
    {   
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->createApplication($data);
    }

    private function extractIdFromIri(string $iri): ?int
    {
        return (preg_match('/([0-9]+)$/', $iri, $matches)) ? (int) $matches[1] : null;
    }
    
    public function createApplication(array $data)
    {
        $this->manager->beginTransaction();
         
        try {
            $application = new Application();

            // -- START -- student
            $studentData = $data['student_array'];

            if(!empty($studentData['id']) && $this->manager->getRepository(Student::class)->find($studentData['id'])) {
                $student = $this->manager->getRepository(Student::class)->find($studentData['id']);

            } else {
                $student = $this->manager->getRepository(Student::class)->findBy(['email' => $studentData['email']])[0] ?? new Student();
                $student
                ->setFirstname($studentData['firstname'] ?? null)
                ->setName($studentData['name'] ?? null)
                ->setGender(Gender::tryFrom($studentData['gender']) ?? null)
                ->setBirthdate($studentData['birthdate'] ? \DateTime::createFromFormat('d/m/Y', $studentData['birthdate']) : null)
                ->setCellphone($studentData['cellphone'] ?? null)
                ->setEmail($studentData['email'] ?? null)
                ->setAddress($studentData['address'] ?? null)
                ->setZipCode($studentData['zip_code'] ?? null)
                ->setCity($studentData['city'] ?? null)
                ->setPersonnalWebsite($studentData['personnal_website'] ?? null)
                ->setDriverLicense($studentData['driver_license'] ?? null)
                ->setPreparedDegree(StudyLevel::tryFrom($studentData['prepared_degree']) ?? null)
                ->setStudyYears(StudyYears::tryFrom($studentData['study_years']) ?? null)
                ->setSchoolName($studentData['school_name'] ?? null)
                ->setVisitorStatus($studentData['visitor_status'] ?? null)
                ;   
                $errors = $this->validator->validate($student);
                if ($errors && count($errors) > 0) {
                    throw new Exception((string) $errors, 404);
                }
            
                $this->manager->persist($student);
            }
            // -- END -- student part

            $application
                ->setStudent($student)
                ->setOffer($this->manager->getRepository(Offer::class)->find($this->extractIdFromIri($data['offer'])))
                ->setMotivations($data['motivations'] ?? '')
                ->setCoverLetter($data['cover_letter']);
            
            $this->manager->persist($application);

            foreach ($data['skills'] as $skill) {
                $application->addSkill($this->manager->getRepository(Skill::class)->find($skill) ?? '');
            }
            
            if(count($data['experiences_array']) > 0 ) {
                foreach($data['experiences_array'] as $item) {
                    $exp = new Experience();
                    $exp
                    ->setCompany($item['company'])
                    ->setType(ExperienceType::tryFrom($item['type']))
                    ->setYear($item['year'])
                    ->setApplication($application)
                    ;
                    $errors = $this->validator->validate($exp);
                    if ($errors && count($errors) > 0) {
                        throw new Exception((string) $errors, 404);
                    }
                    $this->manager->persist($exp);
                }
            }
        $this->manager->flush();

        $this->manager->commit();

        return new JsonResponse(Response::HTTP_CREATED);   

        } catch (Exception $e) {
            $this->manager->rollback();
            throw $e;
        }
    }
}
