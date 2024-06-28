<?php

namespace App\Entity;

use App\Enum\LanguageLevel;
use App\Enum\LanguageName;
use App\Repository\LanguagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguagesRepository::class)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, enumType: LanguageName::class )]
    private LanguageName $name = LanguageName::French;

    #[ORM\Column(length: 255, enumType: LanguageLevel::class)]
    private LanguageLevel $level = LanguageLevel::A1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): LanguageName
    {
        return $this->name;
    }

    public function setName(LanguageName $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): LanguageLevel
    {
        return $this->level;
    }

    public function setLevel(LanguageLevel $level): static
    {
        $this->level = $level;

        return $this;
    }
}
