<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\TypePlat;
use App\Factory\AllergeneFactory;
use App\Factory\BoissonFactory;
use App\Factory\MenuFactory;
use App\Factory\PlatFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    private const boissons = [
        ['lib' => 'Vittel 50cl', 'prix' => 3.00, 'alcoolise' => false, 'descriptionProduit' => 'Eau de source naturelle Vittel.', 'allergenes' => []],
        ['lib' => 'Diabolo Menthe', 'prix' => 4.50, 'alcoolise' => false, 'descriptionProduit' => 'Sirop de menthe et limonade, classique de bistrot.', 'allergenes' => []],
        ['lib' => 'Orangina 25cl', 'prix' => 4.00, 'alcoolise' => false, 'descriptionProduit' => 'Boisson gazeuse fruitée avec pulpe, bouteille iconique.', 'allergenes' => []],
        ['lib' => 'Coupe de Champagne (Brut)', 'prix' => 12.00, 'alcoolise' => true, 'descriptionProduit' => 'Champagne Brut, frais et vif.', 'allergenes' => ['Anhydride sulfureux et sulfites']],
        ['lib' => 'Verre de Bordeaux (Rouge)', 'prix' => 6.50, 'alcoolise' => true, 'descriptionProduit' => 'Vin de Bordeaux A.O.P., rond et fruité.', 'allergenes' => ['Anhydride sulfureux et sulfites']],
    ];

    private const plats = [
        ['lib' => 'Soupe à l\'Oignon Gratinée', 'prix' => 9.00, 'vegetarien' => true, 'typePlat' => '1', 'descriptionProduit' => 'Traditionnelle, avec croûtons et fromage fondu.', 'allergenes' => ['Gluten', 'Lait']],
        ['lib' => 'Escargots de Bourgogne (6 pièces)', 'prix' => 14.00, 'vegetarien' => false, 'typePlat' => '1', 'descriptionProduit' => 'Préparés au beurre persillé et à l\'ail.', 'allergenes' => ['Mollusques', 'Lait']],
        ['lib' => 'Terrine de Campagne', 'prix' => 9.50, 'vegetarien' => false, 'typePlat' => '1', 'descriptionProduit' => 'Servie avec cornichons et pain de campagne.', 'allergenes' => ['Gluten', 'Moutarde']],
        ['lib' => 'Rillettes de Porc Maison', 'prix' => 10.00, 'vegetarien' => false, 'typePlat' => '1', 'descriptionProduit' => 'Servies sur pain grillé, recette de grand-mère.', 'allergenes' => ['Gluten']],
        ['lib' => 'Côte de Bœuf (350g) Sauce au poivre', 'prix' => 32.00, 'vegetarien' => false, 'typePlat' => '2', 'descriptionProduit' => 'Coupée au couteau, maturée, servie avec frites maison.', 'allergenes' => ['Lait']],
        ['lib' => 'Magret de canard, sauce au miel', 'prix' => 24.00, 'vegetarien' => false, 'typePlat' => '2', 'descriptionProduit' => 'Accompagné de pommes de terre sarladaises.', 'allergenes' => []],
        ['lib' => 'Blanquette de Veau à l\'Ancienne', 'prix' => 23.50, 'vegetarien' => false, 'typePlat' => '2', 'descriptionProduit' => 'Morceaux de veau mijotés dans une sauce crémeuse aux champignons.', 'allergenes' => ['Lait', 'Gluten', 'Céleri']],
        ['lib' => 'Cassoulet Toulousain', 'prix' => 25.00, 'vegetarien' => false, 'typePlat' => '2', 'descriptionProduit' => 'Plat du Sud-Ouest à base de haricots blancs, saucisse et confit de canard.', 'allergenes' => ['Céleri']],
        ['lib' => 'Mille-feuille à la vanille', 'prix' => 8.50, 'vegetarien' => true, 'typePlat' => '3', 'descriptionProduit' => 'Pâte feuilletée croustillante et crème pâtissière légère.', 'allergenes' => ['Gluten', 'Lait', 'Œufs']],
        ['lib' => 'Île Flottante', 'prix' => 7.00, 'vegetarien' => true, 'typePlat' => '3', 'descriptionProduit' => 'Meringue légère sur lit de crème anglaise.', 'allergenes' => ['Œufs', 'Lait']],
        ['lib' => 'Moelleux au Chocolat, cœur coulant', 'prix' => 9.00, 'vegetarien' => true, 'typePlat' => '3', 'descriptionProduit' => 'Servi avec une boule de glace vanille.', 'allergenes' => ['Gluten', 'Lait', 'Œufs', 'Soja']],
        ['lib' => 'Assortiment de Fromages Affinés', 'prix' => 11.00, 'vegetarien' => true, 'typePlat' => '3', 'descriptionProduit' => 'Sélection de trois fromages A.O.P. de la région.', 'allergenes' => ['Lait', 'Fruits à coque']],
    ];

    private const menus = [
        [
            'lib' => 'Menu du Jour', 'prix' => 20, 'descriptionProduit' => 'Séléction de nos plats de la journée',
            'compo' => ['Soupe à l\'Oignon Gratinée', 'Terrine de Campagne', 'Blanquette de Veau à l\'Ancienne', 'Cassoulet Toulousain', 'Île Flottante', 'Moelleux au Chocolat, cœur coulant'],
        ],
        [
            'lib' => 'Menu Gourmand', 'prix' => 45.00, 'descriptionProduit' => 'Le meilleur de notre terroir pour les plus fins gourmets.',
            'compo' => ['Escargots de Bourgogne (6 pièces)', 'Rillettes de Porc Maison', 'Côte de Bœuf (350g) Sauce au poivre', 'Magret de canard, sauce au miel', 'Mille-feuille à la vanille', 'Assortiment de Fromages Affinés'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        $typePlatRepository = $manager->getRepository(TypePlat::class);

        foreach ($restaurants as $restaurant) {
            foreach (self::boissons as $boisson) {
                $lstAllergene = [];
                foreach ($boisson['allergenes'] as $allergenes) {
                    $allergene = AllergeneFactory::find(['name' => $allergenes]);
                    if ($allergene) {
                        $lstAllergene[] = $allergene;
                    }
                }
                $newBoisson = BoissonFactory::createOne([
                    'libProduit' => $boisson['lib'],
                    'prixProduit' => $boisson['prix'],
                    'descriptionProduit' => $boisson['descriptionProduit'],
                    'visible' => true,
                    'alcoolise' => $boisson['alcoolise'],
                    'idRestau' => $restaurant,
                    'allergenes' => $lstAllergene,
                ]);
            }

            foreach (self::plats as $plat) {
                $typePlat = $typePlatRepository->findOneBy(['id' => $plat['typePlat']]);
                $lstAllergene = [];
                foreach ($plat['allergenes'] as $allergenes) {
                    $allergene = AllergeneFactory::find(['name' => $allergenes]);
                    if ($allergene) {
                        $lstAllergene[] = $allergene;
                    }
                }
                $newPlat = PlatFactory::createOne([
                    'libProduit' => $plat['lib'],
                    'prixProduit' => $plat['prix'],
                    'descriptionProduit' => $plat['descriptionProduit'],
                    'visible' => true,
                    'vegetarien' => $plat['vegetarien'],
                    'idRestau' => $restaurant,
                    'typePlat' => $typePlat,
                    'allergenes' => $lstAllergene,
                ]);

                $platsCrees[$plat['lib']] = $newPlat;
            }

            foreach (self::menus as $menu) {
                $platsMenu = [];
                foreach ($menu['compo'] as $nomPlat) {
                    $platsMenu[] = $platsCrees[$nomPlat];
                }

                MenuFactory::createOne([
                    'libProduit' => $menu['lib'],
                    'prixProduit' => $menu['prix'],
                    'descriptionProduit' => $menu['descriptionProduit'],
                    'visible' => true,
                    'idRestau' => $restaurant,
                    'idPlat' => $platsMenu,
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
            AllergeneFixtures::class,
        ];
    }
}
