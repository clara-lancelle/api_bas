<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\ExperienceTypes;
use App\Enum\ExperienceType;
use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/experiences/types',
            controller: ExperienceTypes::class,
            name: 'api_experiences_types',
            read: false,
        ),
    ]
)]

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['student', 'application'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Application $application;

    #[ORM\Column(length: 255)]
    #[Groups(['student', 'application'])]

    private ?string $company = null;

    #[ORM\Column(length: 255,  enumType: ExperienceType::class)]
    #[Groups(['student', 'application'])]

    private ExperienceType $type = ExperienceType::Internship;

    #[Assert\Regex('/^((19[0-9]{2})||(201[0-9]{1})||(202[0-4]{1}))$/')]
    #[ORM\Column(length: 4)]
    #[Groups(['student', 'application'])]
    private ?string $year = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

     public function setApplication(?Application $application): static
    {
        $this->application = $application;

        return $this;
    }


    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getType(): ExperienceType
    {
        return $this->type;
    }

    public function setType(ExperienceType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }
}
