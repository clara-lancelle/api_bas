<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    operations: [
        new Post(),
    ]
)]
#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $birthdate = null;

    /**
     * @var Collection<int, Hobbie>
     */
    #[ORM\OneToMany(targetEntity: Hobbie::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $hobbies;

    /**
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $formations;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $experiences;

    /**
     * @var Collection<int, Request>
     */
    #[ORM\OneToMany(targetEntity: Request::class, mappedBy: 'student')]
    private Collection $requests;

    #[ORM\Column(nullable: true)]
    private ?bool $driver_license = null;

    #[ORM\Column(nullable: true)]
    private ?bool $handicap = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $linkedin_page = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    private ?string $personnal_website = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prepared_degree = null;

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->hobbies     = new ArrayCollection();
        $this->formations  = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTime $birthdate): static
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * @return Collection<int, Hobbie>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobbie $hobby): static
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
            $hobby->setStudent($this);
        }

        return $this;
    }

    public function removeHobby(Hobbie $hobby): static
    {
        if ($this->hobbies->removeElement($hobby)) {
            // set the owning side to null (unless already changed)
            if ($hobby->getStudent() === $this) {
                $hobby->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setStudent($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getStudent() === $this) {
                $formation->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    
    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setStudent($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getStudent() === $this) {
                $experience->setStudent(null);
            }
        }

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
            $request->setStudent($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getStudent() === $this) {
                $request->setStudent(null);
            }
        }

        return $this;
    }

    #[Groups('request')]
    public function getCalculatedAge() :int
    {
        return ($this->getBirthdate()->diff(new \DateTime()))->days;
    }

    public function isDriverLicense(): ?bool
    {
        return $this->driver_license;
    }

    public function setDriverLicense(bool $driver_license): static
    {
        $this->driver_license = $driver_license;

        return $this;
    }

    public function isHandicap(): ?bool
    {
        return $this->handicap;
    }

    public function setHandicap(bool $handicap): static
    {
        $this->handicap = $handicap;

        return $this;
    }

    public function getLinkedinPage(): ?string
    {
        return $this->linkedin_page;
    }

    public function setLinkedinPage(?string $linkedin_page): static
    {
        $this->linkedin_page = $linkedin_page;

        return $this;
    }

    public function getPersonnalWebsite(): ?string
    {
        return $this->personnal_website;
    }

    public function setPersonnalWebsite(?string $personnal_website): static
    {
        $this->personnal_website = $personnal_website;

        return $this;
    }

    public function getPreparedDegree(): ?string
    {
        return $this->prepared_degree;
    }

    public function setPreparedDegree(?string $prepared_degree): static
    {
        $this->prepared_degree = $prepared_degree;

        return $this;
    }

}
