<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\StudyYears;
use App\Enum\StudyLevel;
use App\Enum\StudyYear;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['student']],
    operations: [
        new Post(),
        new GetCollection(
            uriTemplate: '/students/StudyYears',
            controller: StudyYears::class,
            name: 'api_students_study_years',
            read: false,
        ),
        new Put(
            uriTemplate: '/security/students/{id}',
            ),
        new Patch(
            uriTemplate: '/security/students/{id}',
        ),
        new GetCollection(
             uriTemplate: '/security/students/', 
        ),
        new Get(),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[Groups('student')]
class Student extends User
{

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $birthdate = null;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'student', orphanRemoval: true,  cascade: ["persist"])]
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

    #[ORM\Column(length: 100, nullable: true, enumType: StudyLevel::class)]
    private StudyLevel $prepared_degree = StudyLevel::Level1;

     #[ORM\Column(length: 255, nullable: true)]
    private ?string $school_name = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $applications;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(length: 255, enumType: StudyYear::class)]
    private StudyYear $study_years = StudyYear::bac0;

    /**
     * @var Collection<int, Language>
     */
    #[ORM\ManyToMany(targetEntity: Language::class, inversedBy: 'students',  cascade: ["persist"])]
    private Collection $languages;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'students')]
    private Collection $skills;

    public function __construct()
    {
        $this->setRoles(['ROLE_USER']);
        $this->experiences = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->skills = new ArrayCollection();
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

    public function getPreparedDegree(): StudyLevel
    {
        return $this->prepared_degree;
    }

    public function setPreparedDegree(StudyLevel $prepared_degree): static
    {
        $this->prepared_degree = $prepared_degree;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setStudent($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getStudent() === $this) {
                $application->setStudent(null);
            }
        }

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getStudyYears(): StudyYear
    {
        return $this->study_years;
    }

    public function setStudyYears(StudyYear $study_years): static
    {
        $this->study_years = $study_years;

        return $this;
    }


    public function getSchoolName(): ?string
    {
        return $this->school_name;
    }

    public function setSchoolName(?string $school_name): static
    {
        $this->school_name = $school_name;

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): static
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
        }

        return $this;
    }

    public function removeLanguage(Language $language): static
    {
        $this->languages->removeElement($language);

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
