<?php

namespace App\Factory;

use App\Entity\Client;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class ClientFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Client::class;
    }

    protected function defaults(): array|callable
    {
        $faker = self::faker();

        return [
            'nom' => self::faker()->lastName(),
            'prenom' => self::faker()->firstName(),
            'telephone' => $faker->numerify('0#########'),
            'email' => self::faker()->email(),
            'roles' => ['ROLE_CLIENT'],
        ];
    }
}
