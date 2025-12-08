<?php

namespace App\DataFixtures;

use App\Factory\TypePlatFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypePlatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TypePlatFactory::createOne([
            'lib' => 'Entrée',
        ]);

        TypePlatFactory::createOne([
            'lib' => 'plat Principal',
        ]);

        TypePlatFactory::createOne([
            'lib' => 'Dessert',
        ]);

        $manager->flush();
    }
}
