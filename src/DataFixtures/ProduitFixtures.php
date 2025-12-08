<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\TypePlat;
use App\Factory\BoissonFactory;
use App\Factory\PlatFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    private const boissons = [
        ['lib' => 'Vittel 50cl', 'prix' => 3.00, 'alcoolise' => false, 'descriptionProduit' => 'Eau de source naturelle Vittel.'],
        ['lib' => 'Diabolo Menthe', 'prix' => 4.50, 'alcoolise' => false, 'descriptionProduit' => 'Sirop de menthe et limonade, classique de bistrot.'],
        ['lib' => 'Orangina 25cl', 'prix' => 4.00, 'alcoolise' => false, 'descriptionProduit' => 'Boisson gazeuse fruitée avec pulpe, bouteille iconique.'],
        ['lib' => 'Coupe de Champagne (Brut)', 'prix' => 12.00, 'alcoolise' => true, 'descriptionProduit' => 'Champagne Brut, frais et vif.'],
        ['lib' => 'Verre de Bordeaux (Rouge)', 'prix' => 6.50, 'alcoolise' => true, 'descriptionProduit' => 'Vin de Bordeaux A.O.P., rond et fruité.'],
    ];

    private const plats = [
        ['lib' => 'Soupe à l\'Oignon Gratinée', 'prix' => 9.00, 'typePlat' => '1', 'descriptionProduit' => 'Traditionnelle, avec croûtons et fromage fondu.'],
        ['lib' => 'Escargots de Bourgogne (6 pièces)', 'prix' => 14.00, 'typePlat' => '1', 'descriptionProduit' => 'Préparés au beurre persillé et à l\'ail.'],
        ['lib' => 'Terrine de Campagne', 'prix' => 9.50, 'typePlat' => '1', 'descriptionProduit' => 'Servie avec cornichons et pain de campagne.'],
        ['lib' => 'Rillettes de Porc Maison', 'prix' => 10.00, 'typePlat' => '1', 'descriptionProduit' => 'Servies sur pain grillé, recette de grand-mère.'],
        ['lib' => 'Côte de Bœuf (350g) Sauce au poivre', 'prix' => 32.00, 'typePlat' => '2', 'descriptionProduit' => 'Coupée au couteau, maturée, servie avec frites maison.'],
        ['lib' => 'Magret de canard, sauce au miel', 'prix' => 24.00, 'typePlat' => '2', 'descriptionProduit' => 'Accompagné de pommes de terre sarladaises.'],
        ['lib' => 'Blanquette de Veau à l\'Ancienne', 'prix' => 23.50, 'typePlat' => '2', 'descriptionProduit' => 'Morceaux de veau mijotés dans une sauce crémeuse aux champignons.'],
        ['lib' => 'Cassoulet Toulousain', 'prix' => 25.00, 'typePlat' => '2', 'descriptionProduit' => 'plat du Sud-Ouest à base de haricots blancs, saucisse et confit de canard.'],
        ['lib' => 'Mille-feuille à la vanille', 'prix' => 8.50, 'typePlat' => '3', 'descriptionProduit' => 'Pâte feuilletée croustillante et crème pâtissière légère.'],
        ['lib' => 'Île Flottante', 'prix' => 7.00, 'typePlat' => '3', 'descriptionProduit' => 'Meringue légère sur lit de crème anglaise.'],
        ['lib' => 'Moelleux au Chocolat, cœur coulant', 'prix' => 9.00, 'typePlat' => '3', 'descriptionProduit' => 'Servi avec une boule de glace vanille.'],
        ['lib' => 'Assortiment de Fromages Affinés', 'prix' => 11.00, 'typePlat' => '3', 'descriptionProduit' => 'Sélection de trois fromages A.O.P. de la région.'],
    ];

    public function load(ObjectManager $manager): void
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        $typePlatRepository = $manager->getRepository(TypePlat::class);

        foreach ($restaurants as $restaurant) {
            foreach (self::boissons as $boisson) {
                BoissonFactory::createOne([
                    'libProduit' => $boisson['lib'],
                    'prixProduit' => $boisson['prix'],
                    'descriptionProduit' => $boisson['descriptionProduit'],
                    'visible' => true,
                    'alcoolise' => $boisson['alcoolise'],
                    'idRestau' => $restaurant,
                ]);
            }

            foreach (self::plats as $plat) {
                $typePlat = $typePlatRepository->findOneBy(['id' => $plat['typePlat']]);

                PlatFactory::createOne([
                    'libProduit' => $plat['lib'],
                    'prixProduit' => $plat['prix'],
                    'descriptionProduit' => $plat['descriptionProduit'],
                    'visible' => true,
                    'idRestau' => $restaurant,
                    'typePlat' => $typePlat,
                ]);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            TypePlatFixtures::class,
        ];
    }
}
