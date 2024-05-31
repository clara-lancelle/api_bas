<?php

namespace App\Factory;

use App\Entity\OfferRequiredProfile;
use App\Repository\OfferRequiredProfileRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<OfferRequiredProfile>
 *
 * @method        OfferRequiredProfile|Proxy                     create(array|callable $attributes = [])
 * @method static OfferRequiredProfile|Proxy                     createOne(array $attributes = [])
 * @method static OfferRequiredProfile|Proxy                     find(object|array|mixed $criteria)
 * @method static OfferRequiredProfile|Proxy                     findOrCreate(array $attributes)
 * @method static OfferRequiredProfile|Proxy                     first(string $sortedField = 'id')
 * @method static OfferRequiredProfile|Proxy                     last(string $sortedField = 'id')
 * @method static OfferRequiredProfile|Proxy                     random(array $attributes = [])
 * @method static OfferRequiredProfile|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OfferRequiredProfileRepository|RepositoryProxy repository()
 * @method static OfferRequiredProfile[]|Proxy[]                 all()
 * @method static OfferRequiredProfile[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static OfferRequiredProfile[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static OfferRequiredProfile[]|Proxy[]                 findBy(array $attributes)
 * @method static OfferRequiredProfile[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static OfferRequiredProfile[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OfferRequiredProfileFactory extends ModelFactory
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
            'text' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(OfferRequiredProfile $offerRequiredProfile): void {})
        ;
    }

    protected static function getClass(): string
    {
        return OfferRequiredProfile::class;
    }
}
