<?php

namespace App\Factory;

use App\Entity\Personne;
use App\Entity\Proprietaire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Proprietaire>
 */
final class ProprietaireFactory extends PersistentProxyObjectFactory
{
    private static int $i = 1;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        private readonly ?UserPasswordHasherInterface $passwordHasher = null,
    ) {
        parent::__construct();
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
            $i = self::$i;

            $prenom = $faker->firstName();
            $nom = $faker->lastName();

            $email = "prop{$i}@example.com";

            ++self::$i;

            return [
                'prenom' => $prenom,
                'nom' => $nom,
                'telephone' => $faker->numerify('0#########'),
                'email' => $email,
                'password' => 'test',
                'roles' => ['ROLE_PROPRIETAIRE'],
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
            ->afterInstantiate(function (Personne $personne) {
                if (null !== $this->passwordHasher) {
                    $personne->setPassword($this->passwordHasher->hashPassword($personne, $personne->getPassword()));
                }
            })
        ;
    }
}
