<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offer $offer = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motivations = null;

    #[ORM\Column(length: 255)]
    private ?string $cover_letter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $school_name = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'applications')]
    private Collection $skills;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\ManyToMany(targetEntity: Experience::class, inversedBy: 'applications')]
    private Collection $experiences;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->experiences = new ArrayCollection();
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

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }

    public function getMotivations(): ?string
    {
        return $this->motivations;
    }

    public function setMotivations(string $motivations): static
    {
        $this->motivations = $motivations;

        return $this;
    }

    public function getCoverLetter(): ?string
    {
        return $this->cover_letter;
    }

    public function setCoverLetter(string $cover_letter): static
    {
        $this->cover_letter = $cover_letter;

        return $this;
    }

    public function getSchoolName(): ?string
    {
        return $this->school_name;
    }

    public function setSchoolName(?string $school_name): static
    {
        $this->school_name = $school_name;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        $this->experiences->removeElement($experience);

        return $this;
    }
}
