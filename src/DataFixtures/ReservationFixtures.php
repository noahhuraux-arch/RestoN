<?php


namespace App\DataFixtures;

use App\Factory\ClientFactory;
use App\Factory\ReservationFactory;
use App\Factory\RestaurantFactory;
use App\Factory\TableFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    // src/DataFixtures/ReservationFixtures.php

    public function load(ObjectManager $manager): void
    {
        $clients = ClientFactory::createMany(20);

        $restaurants = RestaurantFactory::all();

        foreach ($restaurants as $restaurant) {
            $tables = TableFactory::createMany($restaurant->getNbTable(), [
                'restaurant' => $restaurant,
            ]);

            foreach ($tables as $table) {
                ReservationFactory::createMany(2, [
                    'table' => $table,
                    'restaurant' => $restaurant,
                    'client' => $clients[array_rand($clients)],
                ]);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            HorairesFixtures::class,
        ];
    }
}
