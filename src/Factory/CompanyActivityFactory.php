<?php

namespace App\Factory;

use App\Entity\CompanyActivity;
use App\Repository\CompanyActivityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CompanyActivity>
 *
 * @method        CompanyActivity|Proxy                     create(array|callable $attributes = [])
 * @method static CompanyActivity|Proxy                     createOne(array $attributes = [])
 * @method static CompanyActivity|Proxy                     find(object|array|mixed $criteria)
 * @method static CompanyActivity|Proxy                     findOrCreate(array $attributes)
 * @method static CompanyActivity|Proxy                     first(string $sortedField = 'id')
 * @method static CompanyActivity|Proxy                     last(string $sortedField = 'id')
 * @method static CompanyActivity|Proxy                     random(array $attributes = [])
 * @method static CompanyActivity|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CompanyActivityRepository|RepositoryProxy repository()
 * @method static CompanyActivity[]|Proxy[]                 all()
 * @method static CompanyActivity[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CompanyActivity[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CompanyActivity[]|Proxy[]                 findBy(array $attributes)
 * @method static CompanyActivity[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CompanyActivity[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CompanyActivityFactory extends ModelFactory
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
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CompanyActivity $companyActivity): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CompanyActivity::class;
    }
}
