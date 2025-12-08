<?php

namespace App\DataFixtures;

use App\Factory\HoraireFactory;
use App\Factory\RestaurantFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HorairesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $restaurants = RestaurantFactory::all();

        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        foreach ($restaurants as $restaurantProxy) {
            foreach ($jours as $jour) {
                HoraireFactory::new()->create([
                    'restaurant' => $restaurantProxy,
                    'jour' => $jour,
                ]);
            }
        }

        $manager->flush();
    }
}
