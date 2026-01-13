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
        $reservation = 1;
        foreach ($restaurants as $restaurant) {
            $nbTables = $restaurant->getNbTable();
            for ($i = 1; $i <= $nbTables; ++$i) {
                $table = TableFactory::createOne([
                    'restaurant' => $restaurant,
                    'numero' => $i,
                ]);
                for ($j = 0; $j < 2; ++$j) {
                    ReservationFactory::createOne([
                        'table' => $table,
                        'restaurant' => $restaurant,
                        'client' => $clients[array_rand($clients)],
                        'numero' => $reservation,
                    ]);
                    ++$reservation;
                }
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
