<?php

namespace App\Factory;

use App\Entity\JobProfile;
use App\Entity\Offer;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Repository\CompanyRepository;
use App\Repository\JobProfileRepository;
use App\Repository\OfferRepository;
use App\Repository\SkillRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Offer>
 *
 * @method        Offer|Proxy                     create(array|callable $attributes = [])
 * @method static Offer|Proxy                     createOne(array $attributes = [])
 * @method static Offer|Proxy                     find(object|array|mixed $criteria)
 * @method static Offer|Proxy                     findOrCreate(array $attributes)
 * @method static Offer|Proxy                     first(string $sortedField = 'id')
 * @method static Offer|Proxy                     last(string $sortedField = 'id')
 * @method static Offer|Proxy                     random(array $attributes = [])
 * @method static Offer|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OfferRepository|RepositoryProxy repository()
 * @method static Offer[]|Proxy[]                 all()
 * @method static Offer[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Offer[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Offer[]|Proxy[]                 findBy(array $attributes)
 * @method static Offer[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Offer[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OfferFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        private CompanyRepository $companyRepository,
        private JobProfileRepository $jobProfileRepository,
        private SkillRepository $skillRepository
        )
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $jobProfiles = $this->jobProfileRepository->findAll();
        $companies = $this->companyRepository->findAll();
        $skills = $this->skillRepository->findAll();
        return [
            'application_limit_date' => self::faker()->dateTimeInInterval('+1 day', '+2 month'),
            'available_place'        => self::faker()->numberBetween(0, 50),
            'company'                => self::faker()->randomElements($companies)[0],
            'description'            => self::faker()->text(255),
            'duration'               => self::faker()->randomElement(Duration::cases()),
            'end_date'               => self::faker()->dateTime(),
            'name'                   => self::faker()->jobTitle(),
            'remote'                 => self::faker()->text(20),
            'revenue'                => self::faker()->text(20),
            'start_date'             => self::faker()->dateTime(),
            'study_level'            => self::faker()->randomElement(StudyLevel::cases()),
            'type'                   => self::faker()->randomElement(OfferType::cases()),
            'skills' => self::faker()->randomElements($skills, self::faker()->numberBetween(1, 6)),
            'job_profiles' => self::faker()->randomElements($jobProfiles, self::faker()->numberBetween(1, 3)),
            'created_at'             => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'updated_at'             => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Offer $offer): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Offer::class;
    }
}
