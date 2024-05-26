<?php

namespace App\Entity;

use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(length: 255)]
    private ?string $school = null;

    /**
     * @var Collection<int, JobProfile>
     */
    #[ORM\ManyToMany(targetEntity: JobProfile::class, inversedBy: 'requests')]
    private Collection $job_profiles;

    //ENUMS 
    
    #[ORM\Column(length: 255, enumType: OfferType::class)]
    private ?OfferType $type = null;

    #[ORM\Column(length: 255, enumType: StudyLevel::class)]
    private ?StudyLevel $study_level = null;

    #[ORM\Column(length: 255, enumType: Duration::class)]
    private ?Duration $duration = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->job_profiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): static
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection<int, JobProfile>
     */
    public function getJobProfiles(): Collection
    {
        return $this->job_profiles;
    }

    public function addJobProfile(JobProfile $jobProfile): static
    {
        if (!$this->job_profiles->contains($jobProfile)) {
            $this->job_profiles->add($jobProfile);
        }

        return $this;
    }

    public function removeJobProfile(JobProfile $jobProfile): static
    {
        $this->job_profiles->removeElement($jobProfile);

        return $this;
    }

    // -- ENUM getters & setters

    public function getType(): OfferType
    {
        return $this->type;
    }

    public function setType(OfferType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStudylevel(): StudyLevel
    {
        return $this->study_level;
    }

    public function setStudylevel(StudyLevel $study_level): static
    {
        $this->study_level = $study_level;

        return $this;
    }

    public function getDuration(): Duration
    {
        return $this->duration;
    }

    public function setDuration(Duration $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    // -- END ENUM getters & setters

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
