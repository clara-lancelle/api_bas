<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Company;
use App\Entity\CompanyActivity;
use App\Entity\CompanyCategory;
use App\Entity\CompanyUser;
use App\Enum\Gender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersistingUserAndCompanyService
{

    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $hasher, private ValidatorInterface $validator)
    {
    }

    public function createCompanyUserAndCompany(array $data)
    {
        $this->entityManager->beginTransaction();

        try {
            // Create Company
            $company = new Company();
            $company
            ->setName($data['companyName'])
            ->setSiret($data['siret'])
            ->setAddress($data['address'])
            ->setAdditionalAddress($data['additional_address'])
            ->setCity($data['city'])
            ->setZipCode($data['zip_code'])
            ->setPhoneNum($data['phone_num'])
            ->setCategory($this->entityManager->getRepository(CompanyCategory::class)->find($data['category']))
            ;
            
            foreach ($data['activities'] as $activity) {
                $companyActivity = $this->entityManager->getRepository(CompanyActivity::class)->find($activity);
                if ($companyActivity) {
                    $company->addActivity($companyActivity);
                }
            }

            $errors = $this->validator->validate($company);
            if ($errors && count($errors) > 0) {
                return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST);
            }
            $this->entityManager->persist($company);


            // Create Company User
            $user = new CompanyUser();
            $user
            ->setFirstname($data['firstname'])
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setCellphone($data['cellphone'])
            ->setGender(Gender::tryFrom($data['gender']))
            ->setPosition($data['position'])
            ->setCompany($company)
            ;
            
            // Encode the password
            $encodedPassword = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($encodedPassword);

            $errors = $this->validator->validate($user);
            if ($errors && count($errors) > 0) {
                return new JsonResponse((string) $errors,Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($user);
            // Flush and commit the transaction
            $this->entityManager->flush();

            $this->entityManager->commit();

            return new JsonResponse(Response::HTTP_CREATED);   
            
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
    private function extractIdFromIri(string $iri): ?int
    {
        if (preg_match('#/([0-9]+)$#', $iri, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}
