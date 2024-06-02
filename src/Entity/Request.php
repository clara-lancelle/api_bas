<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Enum\Duration;
use App\Enum\OfferType;
use ApiPlatform\Metadata\Get;
use App\Controller\LastRequest;
use App\Enum\StudyLevel;
use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ApiResource(
    normalizationContext: ['groups' => ['request']],
    operations: [
        new GetCollection(
            uriTemplate: '/requests/last',
            controller: LastRequest::class,
            name: 'api_requests_last',
            read: false,
        ),
        new GetCollection(),
        new Get(),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'type' => 'exact', 'job_profile' => 'exact', 'duration' => 'exact', 'study_level' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['created_at' => 'ASC', 'name' => 'ASC', ''])]
#[Groups('request')]
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
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(length: 255)]
    private ?string $school = null;

    // -- START Generate Dates 

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    // -- END Generate Dates 

    /**
     * @var Collection<int, JobProfile>
     */
    #[ORM\ManyToMany(targetEntity: JobProfile::class, inversedBy: 'requests')]
    private Collection $job_profiles;

    // START ENUMS 
    
    #[ORM\Column(length: 255, enumType: OfferType::class)]
    private ?OfferType $type = null;

    #[ORM\Column(length: 255, enumType: StudyLevel::class)]
    private ?StudyLevel $study_level = null;

    #[ORM\Column(length: 255, enumType: Duration::class)]
    private ?Duration $duration = null;

    // -- END ENUMS 

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {

        $this->study_level = StudyLevel::Level1;
        $this->type        = OfferType::Internship;
        $this->duration    = Duration::between2and6months;
        $this->job_profiles = new ArrayCollection();
    }

    // -- START Generate Dates 

    #[ORM\PrePersist]
    public function setCreatedAtValue(): static
    {
        $this->created_at = new \DateTimeImmutable();
        $this->setUpdatedAtValue();
        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): static
    {
        $this->updated_at = new \DateTimeImmutable();
        return $this;
    }

    public function getStatus(): string
    {
        return $this->deleted_at === null ? 'Actif' : 'Inactif';
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    // -- END Generate Dates 


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

    #[Groups('request')]
    public function getCalculatedDuration() :int
    {
        return ($this->getStartDate()->diff($this->getEndDate()))->days;
    }
}
