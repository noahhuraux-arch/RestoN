<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Factory\BoissonFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BoissonFixtures extends Fixture
{
    private const boissons = [
        ['lib' => 'Eau de source 50cl', 'prix' => 3.00, 'alcoolise' => false, 'descriptionProduit' => 'Eau plate.'],
        ['lib' => 'Jus de Pomme Bio', 'prix' => 4.50, 'alcoolise' => false, 'descriptionProduit' => 'Pur jus de pomme.'],
        ['lib' => 'Thé Glacé Maison', 'prix' => 4.00, 'alcoolise' => false, 'descriptionProduit' => 'Infusion fraîche du jour.'],
        ['lib' => 'Bière blonde locale 33cl', 'prix' => 6.50, 'alcoolise' => true, 'descriptionProduit' => 'Bière artisanale, 5.5%.'],
        ['lib' => 'Vin Rouge du Patron', 'prix' => 7.00, 'alcoolise' => true, 'descriptionProduit' => 'Verre de vin rouge de la maison.'],
    ];

    public function load(ObjectManager $manager): void
    {

        $restaurants = $manager->getRepository(Restaurant::class)->findAll();


        foreach ($restaurants as $restaurant) {

            foreach (self::boissons as $boisson) {

                BoissonFactory::createOne([
                    'libProduit' => $boisson['lib'],
                    'prixProduit' => $boisson['prix'],
                    'alcoolise' => $boisson['alcoolise'],
                    'descriptionProduit' => $boisson['desc'],
                    'visible' => true,
                    'idRestau' => $restaurant,
                ]);
            }
        }

        $manager->flush();
    }
}
