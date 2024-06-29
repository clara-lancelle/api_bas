<?php

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Company;
use App\Entity\CompanyActivity;
use App\Entity\CompanyCategory;
use App\Entity\CompanyUser;
use App\Enum\Gender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class PersistingUserAndCompany
{
    public function __construct(private EntityManagerInterface $manager, private UserPasswordHasherInterface $hasher, private ValidatorInterface $validator)
    {   
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        return $this->createCompanyUserAndCompany($data);
    }

     public function createCompanyUserAndCompany(array $data)
    {
        
        $this->manager->beginTransaction();

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
            ->setCategory($this->manager->getRepository(CompanyCategory::class)->find($data['category']))
            ;
            
            foreach ($data['activities'] as $activity) {
                $companyActivity = $this->manager->getRepository(CompanyActivity::class)->find($activity);
                if ($companyActivity) {
                    $company->addActivity($companyActivity);
                }
            }

            $errors = $this->validator->validate($company);
            if ($errors && count($errors) > 0) {
                return new JsonResponse((string) $errors, Response::HTTP_BAD_REQUEST);
            }
            $this->manager->persist($company);


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

            $this->manager->persist($user);
            // Flush and commit the transaction
            $this->manager->flush();

            $this->manager->commit();

            return new JsonResponse(Response::HTTP_CREATED);   
            
        } catch (\Exception $e) {
            $this->manager->rollback();
            throw $e;
        }
    }
}