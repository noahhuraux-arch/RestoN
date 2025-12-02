<?php

namespace App\Factory;

use App\Entity\Restaurant;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Restaurant>
 */
final class RestaurantFactory extends PersistentProxyObjectFactory
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
        return Restaurant::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return function () {
            $faker = self::faker();

            $prenom = $faker->firstName();
            $nom = $faker->lastName();

            $prenomSlug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $prenom));
            $nomSlug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $nom));

            $domain = strtolower($faker->domainName());

            $email = "{$prenomSlug}.{$nomSlug}@{$domain}";

            return [
                'libRestau' => $faker->company(),
                'adr_restau' => $faker->streetAddress(),
                'cp_restau' => $faker->postcode(),
                'ville_restau' => $faker->city(),
                'nb_table' => $faker->numberBetween(5, 40),
                'nb_etoiles' => $faker->numberBetween(0, 3),
                'proprietaire' => ProprietaireFactory::new(),
                'tel_restau' => $faker->numerify('0#########'),
                'email_restau' => $email,
            ];
        };
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Restaurant $restaurant): void {})
        ;
    }
}
