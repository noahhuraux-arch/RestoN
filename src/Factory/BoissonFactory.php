<?php

namespace App\Factory;

use App\Entity\Boisson;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Boisson>
 */
final class BoissonFactory extends PersistentObjectFactory
{
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Boisson::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'libProduit' => 'Boisson default',
            'prixProduit' => self::faker()->randomFloat(2, 2, 15),
            'visible' => true,
            'descriptionProduit' => self::faker()->text(150),
            'alcoolise' => false,
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this;
    }
}
