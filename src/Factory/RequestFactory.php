<?php

namespace App\Factory;

use App\Entity\Request;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
use App\Repository\JobProfileRepository;
use App\Repository\RequestRepository;
use App\Repository\StudentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Request>
 *
 * @method        Request|Proxy                     create(array|callable $attributes = [])
 * @method static Request|Proxy                     createOne(array $attributes = [])
 * @method static Request|Proxy                     find(object|array|mixed $criteria)
 * @method static Request|Proxy                     findOrCreate(array $attributes)
 * @method static Request|Proxy                     first(string $sortedField = 'id')
 * @method static Request|Proxy                     last(string $sortedField = 'id')
 * @method static Request|Proxy                     random(array $attributes = [])
 * @method static Request|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RequestRepository|RepositoryProxy repository()
 * @method static Request[]|Proxy[]                 all()
 * @method static Request[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Request[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Request[]|Proxy[]                 findBy(array $attributes)
 * @method static Request[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Request[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class RequestFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private JobProfileRepository $jobProfileRepository)
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
        return [
            'name' => self::faker()->randomElement([
                'Développeur web',
                'Designer graphique',
                'Gestionnaire de projet',
                'Consultant en marketing digital',
                'Analyste de données'
            ]),
            'school' => self::faker()->randomElement([
                'Université de Paris',
                'École Polytechnique',
                'Université de Lyon',
                'Université de Bordeaux',
                'Université de Nantes'
            ]),
            'description' => self::faker()->paragraph,
            'study_level' => self::faker()->randomElement(StudyLevel::cases()),
            'type' => self::faker()->randomElement(OfferType::cases()),
            'duration' => self::faker()->randomElement(Duration::cases()),
            'start_date' => self::faker()->dateTimeBetween('now', '+1 year'),
            'end_date' => self::faker()->dateTimeBetween('+3 week', '+2 year'),
            'created_at'             => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 year', 'now')),
            'updated_at'             => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 year', 'now')),
            'student' => StudentFactory::new(), // Assuming you have a StudentFactory
            'job_profiles' => self::faker()->randomElements($jobProfiles, self::faker()->numberBetween(1, 3)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Request $request): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Request::class;
    }
}
