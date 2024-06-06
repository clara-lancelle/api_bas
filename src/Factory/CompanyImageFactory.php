<?php

namespace App\Factory;

use App\Entity\CompanyImage;
use App\Repository\CompanyImageRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CompanyImage>
 *
 * @method        CompanyImage|Proxy                     create(array|callable $attributes = [])
 * @method static CompanyImage|Proxy                     createOne(array $attributes = [])
 * @method static CompanyImage|Proxy                     find(object|array|mixed $criteria)
 * @method static CompanyImage|Proxy                     findOrCreate(array $attributes)
 * @method static CompanyImage|Proxy                     first(string $sortedField = 'id')
 * @method static CompanyImage|Proxy                     last(string $sortedField = 'id')
 * @method static CompanyImage|Proxy                     random(array $attributes = [])
 * @method static CompanyImage|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CompanyImageRepository|RepositoryProxy repository()
 * @method static CompanyImage[]|Proxy[]                 all()
 * @method static CompanyImage[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CompanyImage[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CompanyImage[]|Proxy[]                 findBy(array $attributes)
 * @method static CompanyImage[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CompanyImage[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CompanyImageFactory extends ModelFactory
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

    public array $images = [
        'companyImage.png',
        'companyImage1.png',
        'companyImage2.png',
        'companyImage3.png',
        'companyImage4.png',
        'companyImage5.png',
        'companyImage6.png',
        'companyImage7.png',
        'companyImage8.png',
    ];

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(25),
            'path' => self::faker()->randomElement($this->images)
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CompanyImage $companyImage): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CompanyImage::class;
    }
}
