<?php

namespace App\Entity;

use App\Repository\JobProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobProfileRepository::class)]
class JobProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'job_profile')]
    private Collection $offers;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
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

    public static function getJobProfiles(JobProfilRepository $repository): array
    {
        return $repository->findAll();
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setJobprofile($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getJobprofile() === $this) {
                $offer->setJobprofile(null);
            }
        }

        return $this;
    }
}
