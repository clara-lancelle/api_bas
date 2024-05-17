<?php

namespace App\Entity;

use App\Enum\OfferType;
use App\Repository\OfferRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\Column(length: 255, enumType: OfferType::class)]
    private OfferType $type = OfferType::Internship;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $promote_status = null;

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

    #[ORM\ManyToOne(inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Choice(callback: [JobProfile::class, 'getJobProfiles'])]
    private ?JobProfile $job_profile = null;

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

    public function getType(): OfferType
    {
        return $this->type;
    }

    public function setType(OfferType $type): static
    {
        $this->type = $type;

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

    public function getPromoteStatus(): ?string
    {
        return $this->promote_status;
    }

    public function setPromoteStatus(string $promote_status): static
    {
        $this->promote_status = $promote_status;

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

    public function getJobprofile(): ?JobProfile
    {
        return $this->job_profile;
    }

    public function setJobprofile(?JobProfile $job_profile): static
    {
        $this->job_profile = $job_profile;

        return $this;
    }
}
