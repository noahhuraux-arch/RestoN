<?php

// src/Factory/ReservationFactory.php

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

    // src/Factory/ReservationFactory.php

    protected function defaults(): array|callable
    {
        return [
            'date' => self::faker()->dateTimeBetween('now', '+1 month'),
            'nbPers' => self::faker()->numberBetween(1, 8),
            'heure' => self::faker()->dateTime(),
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function (Reservation $reservation): void {
            $restaurant = $reservation->getTable()->getRestaurant();
            $horaires = $restaurant->getHoraires()->filter(fn ($h) => !$h->isFerme());

            if (!$horaires->isEmpty()) {
                $h = self::faker()->randomElement($horaires->toArray());
                $heureBase = $h->getOuvertureMidi() ?? $h->getOuvertureSoir();

                if ($heureBase) {
                    $heure = \DateTime::createFromInterface($heureBase);
                    $heure->modify('+'.self::faker()->randomElement([0, 30, 60, 90]).' minutes');
                    $reservation->setHeure($heure);
                }
            }
        });
    }
}
