<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\JobProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ]
)]
#[ORM\Entity(repositoryClass: JobProfileRepository::class)]
class JobProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('offer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('offer')]
    private ?string $name = null;

    #[ORM\Column(length: 15)]
    #[Groups('offer')]
    private ?string $color = null;

    #[ORM\ManyToOne(inversedBy: 'job_profiles')]
    private ?Offer $offer = null;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\ManyToMany(targetEntity: Request::class, mappedBy: 'job_profiles')]
    private Collection $requests;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

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

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->addJobProfile($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            $request->removeJobProfile($this);
        }

        return $this;
    }
}
