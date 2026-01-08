<?php

namespace App\DataFixtures;

use App\Factory\ClientFactory;
use App\Factory\ReservationFactory;
use App\Factory\RestaurantFactory;
use App\Factory\TableFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    // src/DataFixtures/ReservationFixtures.php

    public function load(ObjectManager $manager): void
    {
        $clients = ClientFactory::createMany(20);

        $restaurants = RestaurantFactory::all();

        foreach ($restaurants as $restaurant) {
            $nbTables = $restaurant->getNbTable();
            for ($i = 1; $i <= $nbTables; ++$i) {
                $table = TableFactory::createOne([
                    'restaurant' => $restaurant,
                    'numero' => $i,
                ]);
                ReservationFactory::createMany(2, [
                    'Table' => $table,
                    'Restaurant' => $restaurant,
                    'Client' => $clients[array_rand($clients)],
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
