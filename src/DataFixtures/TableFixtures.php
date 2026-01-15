<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Factory\TableFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TableFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();

        foreach ($restaurants as $restaurant) {
            $nbTables = $restaurant->getNbTable();
            for ($i = 1; $i <= $nbTables; ++$i) {
                TableFactory::createOne([
                    'numero' => $i,
                    'restaurant' => $restaurant,
                ]);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
        ];
    }
}
