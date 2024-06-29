<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\PersistingApplication;
use App\Repository\ApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ApiResource(
    normalizationContext: ['groups' => ['application']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            uriTemplate: '/applications/persistingApplication',
            controller: PersistingApplication::class,
            name: 'api_persisting_application',
        ),
    ]
)]

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[Groups('application')]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('student')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[SerializedName('student')]
    private ?array $student_array = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('student')]
    private ?Offer $offer = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motivations = null;

    #[ORM\Column(length: 255)]
    private ?string $cover_letter = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'applications')]
    private Collection $skills;

     /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'application', orphanRemoval: true)]
    private Collection $experiences;

    #[SerializedName('experiences')]
    private ?array $experiences_array = null;

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

     public function getStudentArray(): ?array
    {
        return $this->student_array;
    }

    public function setStudentArray(?array $student_array): static
    {
        $this->student_array = $student_array;

        return $this;
    }

     public function getExperiencesArray(): ?array
    {
        return $this->experiences_array;
    }

    public function setExperiencesArray(?array $experiences_array): static
    {
        $this->experiences_array = $experiences_array;

        return $this;
    }
}
