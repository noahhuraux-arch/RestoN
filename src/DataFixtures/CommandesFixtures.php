<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Restaurant;
use App\Entity\Serveur;
use App\Entity\Table;
use App\Factory\CommandeFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommandesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();

        foreach ($restaurants as $restaurant) {
            $produits = $manager->getRepository(Produit::class)->findBy(['restaurant' => $restaurant]);
            $serveurs = $manager->getRepository(Serveur::class)->findBy(['restaurant' => $restaurant]);
            $tables = $manager->getRepository(Table::class)->findBy(['restaurant' => $restaurant]);
            for ($i = 0; $i < 5; ++$i) {
                $unServeur = $serveurs[array_rand($serveurs)];
                $uneTable = $tables[array_rand($tables)];
                shuffle($produits);
                $selection = array_slice($produits, 0, rand(4, 10));
                $total = 0;
                foreach ($selection as $p) {
                    $total += $p->getPrixProduit();
                }

                CommandeFactory::createOne([
                    'restaurant' => $restaurant,
                    'serveur' => $unServeur,
                    'tables' => $uneTable,
                    'produits' => $selection,
                    'prixCommande' => $total,
                ]);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            ProduitFixtures::class,
            ServeurFixtures::class,
            TableFixtures::class,
        ];
    }
}
