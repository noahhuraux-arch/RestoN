<?php

namespace App\Factory;

use App\Entity\Serveur;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Serveur>
 */
final class ServeurFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Serveur::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'prenom' => self::faker()->firstName(),
            'nom' => self::faker()->lastName(),
            'telephone' => self::faker()->numerify('0#########'),
            'email' => self::faker()->unique()->safeEmail(),
            'motdepasse' => 'password123',
            'salaire' => self::faker()->randomFloat(2, 1200, 2500),
            'restaurant' => RestaurantFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Serveur $serveur): void {})
        ;
    }
}
