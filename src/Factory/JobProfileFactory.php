<?php

namespace App\Factory;

use App\Entity\JobProfile;
use App\Repository\JobProfileRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<JobProfile>
 *
 * @method        JobProfile|Proxy                     create(array|callable $attributes = [])
 * @method static JobProfile|Proxy                     createOne(array $attributes = [])
 * @method static JobProfile|Proxy                     find(object|array|mixed $criteria)
 * @method static JobProfile|Proxy                     findOrCreate(array $attributes)
 * @method static JobProfile|Proxy                     first(string $sortedField = 'id')
 * @method static JobProfile|Proxy                     last(string $sortedField = 'id')
 * @method static JobProfile|Proxy                     random(array $attributes = [])
 * @method static JobProfile|Proxy                     randomOrCreate(array $attributes = [])
 * @method static JobProfileRepository|RepositoryProxy repository()
 * @method static JobProfile[]|Proxy[]                 all()
 * @method static JobProfile[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static JobProfile[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static JobProfile[]|Proxy[]                 findBy(array $attributes)
 * @method static JobProfile[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static JobProfile[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class JobProfileFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
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
            'color' => self::faker()->hexColor(),
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(JobProfile $jobProfile): void {})
        ;
    }

    protected static function getClass(): string
    {
        return JobProfile::class;
    }
}
