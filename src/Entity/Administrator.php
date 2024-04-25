<?php

namespace App\Entity;

use App\Repository\AdministratorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministratorRepository::class)]
class Administrator extends User
{
    public function __toString(): string
    {
        return $this->getFirstname() . ' ' . $this->getName();
    }
}
