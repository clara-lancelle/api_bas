<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\LanguageLevels;
use App\Controller\LanguageNames;
use App\Enum\LanguageLevel;
use App\Enum\LanguageName;
use App\Repository\LanguagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/languages/levels',
            controller: LanguageLevels::class,
            name: 'api_languages_levels',
            
        ),
        new GetCollection(
            uriTemplate: '/languages/names',
            controller: LanguageNames::class,
            name: 'api_languages_names',
         
        ),
    ]
)]

#[ORM\Entity(repositoryClass: LanguagesRepository::class)]
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

    /**
     * @var Collection<int, Student>
     */
    #[ORM\ManyToMany(targetEntity: Student::class, mappedBy: 'languages')]
    private Collection $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->addLanguage($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            $student->removeLanguage($this);
        }

        return $this;
    }
}
