<?php

namespace App\Factory;

use App\Entity\Reservation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class ReservationFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Reservation::class;
    }
    protected function defaults(): array|callable
    {
        return [
            'date' => self::faker()->dateTimeBetween('now', '+1 month'),
            'heure' => self::faker()->dateTimeBetween('12:00', '22:00'),
            'nbPers' => self::faker()->numberBetween(1, 8),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
