<?php

namespace App\DataFixtures;

use App\Factory\ProprietaireFactory;
use App\Factory\RestaurantFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $noms = [
            'The Rustic Fork',
            'Spice Route Chicken',
            'Blue Fin Sushi',
            'Bella Luna Pizzeria',
            'The Daily Grind Café',
            'Urban Burger Co',
        ];

        $proprioCommun = ProprietaireFactory::new()->create();

        for ($i = 0; $i < 2; ++$i) {
            $this->createRestaurantWithName($noms[$i], $proprioCommun);
        }

        for ($i = 2; $i < count($noms); ++$i) {
            $this->createRestaurantWithName($noms[$i]);
        }
    }

    private function createRestaurantWithName(string $nom, $proprietaire = null): void
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $nom);
        $slug = strtolower($slug);
        $slug = str_replace([' ', "'", '"'], ['-', '', ''], $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);

        $email = "contact@{$slug}.com";

        RestaurantFactory::new()->create([
            'libRestau' => $nom,
            'email_restau' => $email,
            'proprietaire' => $proprietaire ?: ProprietaireFactory::new(),
        ]);
    }
}
