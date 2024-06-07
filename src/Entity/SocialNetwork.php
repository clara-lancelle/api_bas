<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use App\Repository\SocialNetworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ]
)]
#[ORM\Entity(repositoryClass: SocialNetworkRepository::class)]
class SocialNetwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('company')]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups('company')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('company')]
    private ?string $logo = null;

    /**
     * @var Collection<int, SocialLink>
     */
    #[ORM\OneToMany(targetEntity: SocialLink::class, mappedBy: 'social_network')]
    private Collection $socialLinks;

    public function __construct()
    {
        $this->socialLinks = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, SocialLink>
     */
    public function getSocialLinks(): Collection
    {
        return $this->socialLinks;
    }

    public function addSocialLink(SocialLink $socialLink): static
    {
        if (!$this->socialLinks->contains($socialLink)) {
            $this->socialLinks->add($socialLink);
            $socialLink->setSocialNetwork($this);
        }

        return $this;
    }

    public function removeSocialLink(SocialLink $socialLink): static
    {
        if ($this->socialLinks->removeElement($socialLink)) {
            // set the owning side to null (unless already changed)
            if ($socialLink->getSocialNetwork() === $this) {
                $socialLink->setSocialNetwork(null);
            }
        }

        return $this;
    }
}
