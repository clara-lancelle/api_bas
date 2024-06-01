<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\CompanyWithMostOffers;
use App\Repository\CompanyRepository;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\WorkforceRanges;
use App\Enum\WorkforceRange;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['company']],
    operations: [
        new Get(
            uriTemplate: '/companies/mostOffersList',
            controller: CompanyWithMostOffers::class,
            name: 'api_companies_most_offers_list',
            read: false,
            openapiContext: [
                'summary'     => 'Obtenir la liste des 5 entreprises possédant le plus d\'offres actives',
                'description' => 'Retourne la liste des 5 entreprises possédant le plus d\'offres actives dans la base de données'
            ]
            ),
            new GetCollection(
                uriTemplate: '/companies/workforce_ranges',
                controller: WorkforceRanges::class,
                name: 'api_offers_workforce_ranges',
                read: false,
            ),
        new Get(),
        new GetCollection()
    ]
)]


#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'exact', 'activities', 'categories', 'workforce_range'])]
#[ApiFilter(OrderFilter::class, properties: ['created_at' => 'ASC', 'name', ], arguments: ['orderParameterName' => 'order'])]
#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Groups('company')]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('offer')]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups('offer')]
    private ?string $name = null;

    #[Assert\Url]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website_url = null;

    #[ORM\Column(length: 255)]
    private ?string $social_reason = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $workforce = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $turnover = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups('offer')]
    private ?string $city = null;

    #[Assert\Regex('/^[0-9]{5}$/')]
    #[ORM\Column(length: 255)]
    private ?string $zip_code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $creation_date = null;

    #[ORM\Column(length: 255)]
    private ?string $phone_num = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: CompanyUser::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $companyAdministrators;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'company', orphanRemoval: true)]
    private Collection $offers;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/\.(jpeg|jpg|png|gif|webp)$/i',
        message: 'Veuillez télécharger un fichier image valide avec l\'une des extensions suivantes : jpeg, jpg, png, gif, webp.'
    )]
    private ?string $large_image = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/\.(jpeg|jpg|png|gif|webp)$/i',
        message: 'Veuillez télécharger un fichier image valide avec l\'une des extensions suivantes : jpeg, jpg, png, gif, webp.'
    )]
    #[Groups('offer')]
    private ?string $picto_image = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?CompanyActivity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    private ?CompanyCategory $category = null;

    #[ORM\Column(length: 255, enumType: WorkforceRange::class)]
    private ?WorkforceRange $workforce_range = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $additional_address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $schedules = null;

    public function __construct()
    {
        $this->companyAdministrators = new ArrayCollection();
        $this->created_at            = new DateTimeImmutable();
        $this->updated_at            = new DateTimeImmutable();
        $this->offers                = new ArrayCollection();
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setWorkforceRangeByWorkforceValue(): static
    {
        $workforce = (int) $this->getWorkforce();
        if ($workforce < 10) {
            $this->workforce_range = WorkforceRange::lessThanTen;
        } elseif ($workforce < 50) {
            $this->workforce_range = WorkforceRange::TenToFifty;
        } elseif ($workforce < 100) {
            $this->workforce_range = WorkforceRange::FiftyToHundred;
        } elseif ($workforce < 250) {
            $this->workforce_range = WorkforceRange::HundredToTwoHundred;
        } elseif ($workforce < 1000) {
            $this->workforce_range = WorkforceRange::TwoHundredToThousand;
        } else {
            $this->workforce_range = WorkforceRange::moreThanThousand;
        }

        return $this;
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

    public function getWebsiteUrl(): ?string
    {
        return $this->website_url;
    }

    public function setWebsiteUrl(?string $website_url): static
    {
        $this->website_url = $website_url;

        return $this;
    }

    public function getSocialReason(): ?string
    {
        return $this->social_reason;
    }

    public function setSocialReason(string $social_reason): static
    {
        $this->social_reason = $social_reason;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getWorkforce(): ?string
    {
        return $this->workforce;
    }

    public function setWorkforce(?string $workforce): static
    {
        $this->workforce = $workforce;

        return $this;
    }

    public function getTurnover(): ?string
    {
        return $this->turnover;
    }

    public function setTurnover(?string $turnover): static
    {
        $this->turnover = $turnover;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creation_date;
    }

    public function setCreationDate(?\DateTime $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getPhoneNum(): ?string
    {
        return $this->phone_num;
    }

    public function setPhoneNum(string $phone_num): static
    {
        $this->phone_num = $phone_num;

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
    
    /**
     * @return Collection<int, CompanyUser>
     */
    public function getAdministrators(): Collection
    {
        return $this->companyAdministrators;
    }

    public function addAdministrator(CompanyUser $companyAdministrators): static
    {
        if (!$this->companyAdministrators->contains($companyAdministrators)) {
            $this->companyAdministrators->add($companyAdministrators);
            $companyAdministrators->setCompany($this);
        }

        return $this;
    }

    public function removeAdministrator(CompanyUser $companyAdministrators): static
    {
        if ($this->companyAdministrators->removeElement($companyAdministrators)) {
            // set the owning side to null (unless already changed)
            if ($companyAdministrators->getCompany() === $this) {
                $companyAdministrators->setCompany(null);
            }
        }

        return $this;
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

    public function getStatus(): string
    {
        return $this->deleted_at === null ? 'Actif' : 'Inactif';
    }

    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setCompany($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getCompany() === $this) {
                $offer->setCompany(null);
            }
        }

        return $this;
    }

    public function getNumberOfOffers(): int
    {
        return count($this->offers);
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getLargeImage(): ?string
    {
        return $this->large_image;
    }

    public function setLargeImage(string $large_image): static
    {
        $this->large_image = $large_image;

        return $this;
    }

    public function getPictoImage(): ?string
    {
        return $this->picto_image;
    }

    public function setPictoImage(string $picto_image): static
    {
        $this->picto_image = $picto_image;

        return $this;
    }

    public function getActivity(): ?CompanyActivity
    {
        return $this->activity;
    }

    public function setActivity(?CompanyActivity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getCategory(): ?CompanyCategory
    {
        return $this->category;
    }

    public function setCategory(?CompanyCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getWorkforceRange(): WorkforceRange
    {
        return $this->workforce_range;
    }

    public function setWorkforceRange(WorkforceRange $workforce_range): static
    {
        $this->workforce_range = $workforce_range;
        return $this;
    }

    public function getAdditionalAddress(): ?string
    {
        return $this->additional_address;
    }

    public function setAdditionalAddress(?string $additional_address): static
    {
        $this->additional_address = $additional_address;

        return $this;
    }

    public function getSchedules(): ?string
    {
        return $this->schedules;
    }

    public function setSchedules(?string $schedules): static
    {
        $this->schedules = $schedules;

        return $this;
    }
}