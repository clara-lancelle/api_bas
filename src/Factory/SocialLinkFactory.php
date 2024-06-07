<?php

namespace App\Factory;

use App\Entity\SocialLink;
use App\Repository\SocialLinkRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<SocialLink>
 *
 * @method        SocialLink|Proxy                     create(array|callable $attributes = [])
 * @method static SocialLink|Proxy                     createOne(array $attributes = [])
 * @method static SocialLink|Proxy                     find(object|array|mixed $criteria)
 * @method static SocialLink|Proxy                     findOrCreate(array $attributes)
 * @method static SocialLink|Proxy                     first(string $sortedField = 'id')
 * @method static SocialLink|Proxy                     last(string $sortedField = 'id')
 * @method static SocialLink|Proxy                     random(array $attributes = [])
 * @method static SocialLink|Proxy                     randomOrCreate(array $attributes = [])
 * @method static SocialLinkRepository|RepositoryProxy repository()
 * @method static SocialLink[]|Proxy[]                 all()
 * @method static SocialLink[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static SocialLink[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static SocialLink[]|Proxy[]                 findBy(array $attributes)
 * @method static SocialLink[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static SocialLink[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class SocialLinkFactory extends ModelFactory
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
            'url' => self::faker()->url(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(SocialLink $socialLink): void {})
        ;
    }

    protected static function getClass(): string
    {
        return SocialLink::class;
    }
}
