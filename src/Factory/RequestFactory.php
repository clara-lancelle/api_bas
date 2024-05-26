<?php

namespace App\Factory;

use App\Entity\Request;
use App\Enum\Duration;
use App\Enum\OfferType;
use App\Enum\StudyLevel;
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
    public function __construct(private StudentRepository $studentRepository)
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
        return [
            'duration' => self::faker()->randomElement(Duration::cases()),
            'end_date' => self::faker()->dateTime(),
            'name' => self::faker()->text(25),
            'school' => self::faker()->text(25),
            'start_date' => self::faker()->dateTime(),
            'description' => self::faker()->text(),
            'student' => StudentFactory::new(),
            'study_level' => self::faker()->randomElement(StudyLevel::cases()),
            'type' => self::faker()->randomElement(OfferType::cases()),
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
