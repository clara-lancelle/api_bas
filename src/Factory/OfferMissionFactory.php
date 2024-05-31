<?php

namespace App\Factory;

use App\Entity\OfferMission;
use App\Repository\OfferMissionRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<OfferMission>
 *
 * @method        OfferMission|Proxy                     create(array|callable $attributes = [])
 * @method static OfferMission|Proxy                     createOne(array $attributes = [])
 * @method static OfferMission|Proxy                     find(object|array|mixed $criteria)
 * @method static OfferMission|Proxy                     findOrCreate(array $attributes)
 * @method static OfferMission|Proxy                     first(string $sortedField = 'id')
 * @method static OfferMission|Proxy                     last(string $sortedField = 'id')
 * @method static OfferMission|Proxy                     random(array $attributes = [])
 * @method static OfferMission|Proxy                     randomOrCreate(array $attributes = [])
 * @method static OfferMissionRepository|RepositoryProxy repository()
 * @method static OfferMission[]|Proxy[]                 all()
 * @method static OfferMission[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static OfferMission[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static OfferMission[]|Proxy[]                 findBy(array $attributes)
 * @method static OfferMission[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static OfferMission[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class OfferMissionFactory extends ModelFactory
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
            // ->afterInstantiate(function(OfferMission $offerMission): void {})
        ;
    }

    protected static function getClass(): string
    {
        return OfferMission::class;
    }
}
