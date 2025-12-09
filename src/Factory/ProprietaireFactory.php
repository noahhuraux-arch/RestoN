<?php

namespace App\Factory;

use App\Entity\Proprietaire;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Proprietaire>
 */
final class ProprietaireFactory extends PersistentProxyObjectFactory
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
        return Proprietaire::class;
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

            $email = null;

            if (null !== $prenom && null !== $nom) {
                $prenomSlug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $prenom));
                $nomSlug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $nom));
                $domain = strtolower($faker->domainName());
                $email = "{$prenomSlug}.{$nomSlug}@{$domain}";
            }

            return [
                'prenom' => $prenom,
                'nom' => $nom,
                'telephone' => $faker->numerify('0#########'),
                'email' => $email,
                'password' => $faker->password(8, 20),
                'roles' => [],
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
            // ->afterInstantiate(function(Proprietaire $proprietaire): void {})
        ;
    }
}
