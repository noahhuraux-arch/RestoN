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
    public function load(ObjectManager $manager): void
    {
        $restaurants = $manager->getRepository(Restaurant::class)->findAll();
        $typePlatRepository = $manager->getRepository(TypePlat::class);

        $chemin = __DIR__.'/data/produit.json';
        $contenu = file_get_contents($chemin);
        $contenu = json_decode($contenu, true);

        foreach ($restaurants as $restaurant) {
            $platsCrees = [];
            foreach ($contenu['boissons'] as $boisson) {
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
                    'restaurant' => $restaurant,
                    'allergenes' => $lstAllergene,
                ]);
            }

            foreach ($contenu['plats'] as $plat) {
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
                    'restaurant' => $restaurant,
                    'typePlat' => $typePlat,
                    'allergenes' => $lstAllergene,
                ]);

                $platsCrees[$plat['lib']] = $newPlat;
            }

            foreach ($contenu['menus'] as $menu) {
                $platsMenu = [];
                foreach ($menu['compo'] as $nomPlat) {
                    $platsMenu[] = $platsCrees[$nomPlat];
                }

                MenuFactory::createOne([
                    'libProduit' => $menu['lib'],
                    'prixProduit' => $menu['prix'],
                    'descriptionProduit' => $menu['descriptionProduit'],
                    'visible' => true,
                    'restaurant' => $restaurant,
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
