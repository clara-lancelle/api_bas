<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\LanguageLevels;
use App\Controller\LanguageNames;
use App\Enum\LanguageLevel;
use App\Enum\LanguageName;
use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    normalizationContext: ['groups' => ['language']],
    operations: [
        new GetCollection(
            uriTemplate: '/languages/levels',
            controller: LanguageLevels::class,
            name: 'api_languages_levels',
            read: false,
        ),
        new GetCollection(
            uriTemplate: '/languages/names',
            controller: LanguageNames::class,
            name: 'api_languages_names',
            read: false,
        ),
        new GetCollection(
            uriTemplate: '/languages'
        ),  
        new Get(),
        new Patch(
            uriTemplate: '/security/languages/{id}',
        ),
    ]
)]
#[ORM\Entity(repositoryClass: LanguageRepository::class)]
#[Groups('language')]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('student')]
    private ?int $id = null;

    #[ORM\Column(length: 255, enumType: LanguageName::class )]
    #[Groups('student')]
    private LanguageName $name = LanguageName::French;

    #[ORM\Column(length: 255, enumType: LanguageLevel::class)]
    #[Groups('student')]
    private LanguageLevel $level = LanguageLevel::A1;

    #[ORM\ManyToOne(inversedBy: 'languages')]
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): LanguageName
    {
        return $this->name;
    }

    public function setName(LanguageName $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): LanguageLevel
    {
        return $this->level;
    }

    public function setLevel(LanguageLevel $level): static
    {
        $this->level = $level;

        return $this;
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
}
