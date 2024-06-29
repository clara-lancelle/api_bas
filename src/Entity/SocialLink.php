<?php

namespace App\Entity;

use App\Repository\SocialLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SocialLinkRepository::class)]
class SocialLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['company'])]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'socialLinks')]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'socialLinks')]
    #[Groups('company')]
    private ?SocialNetwork $social_network = null;

    public function __toString(): string
    {
        return $this->getUrl();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
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

    public function getSocialNetwork(): ?SocialNetwork
    {
        return $this->social_network;
    }

    public function setSocialNetwork(?SocialNetwork $social_network): static
    {
        $this->social_network = $social_network;

        return $this;
    }
}
