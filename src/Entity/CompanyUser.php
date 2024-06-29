<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CompanyUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['company_user']],
    operations: [
        new Post(),
        new Get(),
        new GetCollection(
             uriTemplate: '/security/company_users/'
        ),
        new Put(
             uriTemplate: '/security/company_users/{id}',
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: CompanyUserRepository::class)]
#[Groups('company_user')]
class CompanyUser extends User
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups('company')]
    private ?string $position = null;

    #[ORM\Column(nullable: true)]
    private ?string $officePhone = null;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'companyUsers')]
    private ?Company $company = null;

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getOfficePhone(): ?string
    {
        return $this->officePhone;
    }

    public function setOfficePhone(?string $officePhone): static
    {
        $this->officePhone = $officePhone;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
    }
}