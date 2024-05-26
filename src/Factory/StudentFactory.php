<?php

namespace App\Factory;

use App\Entity\Student;
use App\Enum\Gender;
use App\Repository\StudentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Student>
 *
 * @method        Student|Proxy                     create(array|callable $attributes = [])
 * @method static Student|Proxy                     createOne(array $attributes = [])
 * @method static Student|Proxy                     find(object|array|mixed $criteria)
 * @method static Student|Proxy                     findOrCreate(array $attributes)
 * @method static Student|Proxy                     first(string $sortedField = 'id')
 * @method static Student|Proxy                     last(string $sortedField = 'id')
 * @method static Student|Proxy                     random(array $attributes = [])
 * @method static Student|Proxy                     randomOrCreate(array $attributes = [])
 * @method static StudentRepository|RepositoryProxy repository()
 * @method static Student[]|Proxy[]                 all()
 * @method static Student[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Student[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Student[]|Proxy[]                 findBy(array $attributes)
 * @method static Student[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Student[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class StudentFactory extends ModelFactory
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
            'birthdate' => self::faker()->dateTime(),
            'cellphone' => '0615141615',
            'city' => self::faker()->city(),
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'email' => self::faker()->email(),
            'firstname' => self::faker()->firstName(),
            'gender' => self::faker()->randomElement(Gender::cases()),
            'name' => self::faker()->name(),
            'password' => self::faker()->password(),
            'profile_image' => 'usr.png',
            'roles' => ['ROLE_USER'],
            'updated_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'zipCode' => '77258',
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Student $student): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Student::class;
    }
}
