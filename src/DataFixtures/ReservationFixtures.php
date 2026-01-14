<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\Table;
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
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        foreach ($restaurants as $restaurant) {
            $reservation = 1;
            $tables = $manager->getRepository(Table::class)->findBy(['restaurant' => $restaurant]);
            foreach ($tables as $table) {
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
            TableFixtures::class,
        ];
    }
}
