<?php

namespace App\DataFixtures;

use App\Factory\AllergeneFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AllergeneFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $chemin = __DIR__.'/data/allergene.json';
        $contenu = file_get_contents($chemin);
        $allergenes = json_decode($contenu, true);

        foreach ($allergenes as $allergene) {
            AllergeneFactory::createOne([
                'name' => $allergene['name'],
            ]);
        }

        $manager->flush();
    }
}
