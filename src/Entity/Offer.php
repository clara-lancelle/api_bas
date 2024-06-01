<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Durations;
use App\Controller\OfferCount;
use App\Controller\StudyLevels;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    normalizationContext: ['groups' => ['offer']],
    operations: [
        new GetCollection(
            uriTemplate: '/offers/count',
            controller: OfferCount::class,
            name: 'api_offers_count',
            read: false,
        ),
        new GetCollection(
            uriTemplate: '/offers/studyLevels',
            controller: StudyLevels::class,
            name: 'api_offers_study_levels',
            read: false,
        ),
        new GetCollection(
            uriTemplate: '/offers/durations',
            controller: Durations::class,
            name: 'api_offers_durations',
            read: false,    
        ),
        new GetCollection(
            uriTemplate: '/offers'
        ),  
        new Get(),
    ]
)]

#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'type' => 'exact', 'job_profiles' => 'exact', 'duration' => 'exact', 'study_level' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['created_at' => 'ASC', 'name', 'application_limit_date' ], arguments: ['orderParameterName' => 'order'])]
#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[Groups('offer')]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $company;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups('company')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])]
    private ?\DateTimeInterface $end_date = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $revenue = null;

    #[ORM\Column(length: 255)]
    private ?string $remote = null;

    #[ORM\Column]
    private ?int $available_place = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'])]
    private ?\DateTimeInterface $application_limit_date = null;

    // -- ENUM

    #[ORM\Column(length: 255, enumType: OfferType::class)]
    #[Groups('company')]
    private ?OfferType $type = null;

    #[ORM\Column(length: 255, enumType: StudyLevel::class)]
    private ?StudyLevel $study_level = null;

    #[ORM\Column(length: 255, enumType: Duration::class)]
    private ?Duration $duration = null;

    /**
     * @var Collection<int, JobProfile>
     */
    #[ORM\ManyToMany(targetEntity: JobProfile::class, inversedBy: 'offers', cascade: ["persist"])]
    #[ORM\JoinTable(name: 'offer_job_profile')]
    #[Groups('offer')]
    private Collection $job_profiles;

    /**
     * @var Collection<int, OfferMission>
     */
    #[ORM\OneToMany(targetEntity: OfferMission::class, mappedBy: 'offer', cascade: ["persist"])]
    private Collection $missions;

    /**
     * @var Collection<int, OfferRequiredProfile>
     */
    #[ORM\OneToMany(targetEntity: OfferRequiredProfile::class, mappedBy: 'offer', cascade: ["persist"])]
    private Collection $required_profiles;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'offers')]
    private Collection $skills;


    // END ENUM --


    public function __construct()
    {
        $this->study_level = StudyLevel::Level1;
        $this->type        = OfferType::Internship;
        $this->duration    = Duration::between2and6months;
        $this->job_profiles = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->required_profiles = new ArrayCollection();
        $this->skills = new ArrayCollection();
    }

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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRevenue(): ?string
    {
        return $this->revenue;
    }

    public function setRevenue(string $revenue): static
    {
        $this->revenue = $revenue;

        return $this;
    }

    public function getRemote(): ?string
    {
        return $this->remote;
    }

    public function setRemote(string $remote): static
    {
        $this->remote = $remote;

        return $this;
    }

    public function getAvailablePlace(): ?int
    {
        return $this->available_place;
    }

    public function setAvailablePlace(int $available_place): static
    {
        $this->available_place = $available_place;

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

    public function getApplicationLimitDate(): ?\DateTimeInterface
    {
        return $this->application_limit_date;
    }

    public function setApplicationLimitDate(\DateTimeInterface $application_limit_date): static
    {
        $this->application_limit_date = $application_limit_date;

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

    #[Groups('offer')]
    public function getCalculatedDuration() :int
    {
        return ($this->getStartDate()->diff($this->getEndDate()))->days;
    }

    #[Groups('offer')]
    public function getCalculatedLimitDays() :int
    {
        return ($this->getApplicationLimitDate()->diff(new \DateTime()))->days;
    }

    /**
     * @return Collection<int, OfferMission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(OfferMission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setOffer($this);
        }

        return $this;
    }

    public function removeMission(OfferMission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getOffer() === $this) {
                $mission->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OfferRequiredProfile>
     */
    public function getRequiredProfiles(): Collection
    {
        return $this->required_profiles;
    }

    public function addRequiredProfile(OfferRequiredProfile $requiredProfile): static
    {
        if (!$this->required_profiles->contains($requiredProfile)) {
            $this->required_profiles->add($requiredProfile);
            $requiredProfile->setOffer($this);
        }

        return $this;
    }

    public function removeRequiredProfile(OfferRequiredProfile $requiredProfile): static
    {
        if ($this->required_profiles->removeElement($requiredProfile)) {
            // set the owning side to null (unless already changed)
            if ($requiredProfile->getOffer() === $this) {
                $requiredProfile->setOffer(null);
            }
        }

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

}
