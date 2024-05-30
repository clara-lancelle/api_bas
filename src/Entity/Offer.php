<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Odm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ODM\MongoDB\Types\Type;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\ApprenticeshipOffers;
use App\Controller\InternshipOffers;
use App\Controller\LastOffers;
use App\Controller\OfferCount;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Repository\OfferRepository;
use App\State\OfferProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/offers/last',
            controller: LastOffers::class,
            name: 'api_offers_last',
            read: false,
            openapiContext: [
                'summary'     => 'Obtenir les dernières offres',
                'description' => 'Retourne les dernières offres dans la base de données'
            ]
        ),
        new GetCollection(
            uriTemplate: '/offers/count',
            controller: OfferCount::class,
            name: 'api_offers_count',
            read: false,
            openapiContext: [
                'summary'     => 'Obtenir le nombre d\'offres de stage',
                'description' => 'Retourne le nombre d\'offres de stage dans la base de données'
            ]
        ),
        new Get(),
        new GetCollection(),
    ]
)]
#[ApiResource(provider: OfferProvider::class)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'type' => 'exact', 'job_profile.name' => 'exact', 'duration' => 'exact', 'study_level' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['created_at' => 'ASC', 'name' => 'ASC', 'application_limit_date' => 'ASC' ])]
#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
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
    private ?\DateTimeInterface $application_limit_date = null;

    // -- ENUM

    #[ORM\Column(length: 255, enumType: OfferType::class)]
    private ?OfferType $type = null;

    #[ORM\Column(length: 255, enumType: StudyLevel::class)]
    private ?StudyLevel $study_level = null;

    #[ORM\Column(length: 255, enumType: Duration::class)]
    private ?Duration $duration = null;

    /**
     * @var Collection<int, JobProfile>
     */
    #[ORM\OneToMany(targetEntity: JobProfile::class, mappedBy: 'offer')]
    private Collection $job_profiles;

    // END ENUM --

    public function __construct()
    {
        $this->study_level = StudyLevel::Level1;
        $this->type        = OfferType::Internship;
        $this->duration    = Duration::between2and6months;
        $this->job_profiles = new ArrayCollection();
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
}
