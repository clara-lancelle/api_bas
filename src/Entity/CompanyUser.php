<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CompanyUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(),
        new Get(),
        new GetCollection(),
        new Patch(),
    ]
)]
#[ORM\Entity(repositoryClass: CompanyUserRepository::class)]
class CompanyUser extends User
{

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(nullable: true)]
    private ?string $officePhone = null;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'companyAdministrators')]
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