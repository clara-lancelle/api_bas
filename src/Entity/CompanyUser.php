<?php

namespace App\Entity;

use App\Repository\CompanyUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyUserRepository::class)]
class CompanyUser extends User
{

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(nullable: true)]
    private ?int $officePhone = null;

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getOfficePhone(): ?int
    {
        return $this->officePhone;
    }

    public function setOfficePhone(?int $officePhone): static
    {
        $this->officePhone = $officePhone;

        return $this;
    }
}
