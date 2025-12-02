<?php

namespace App\DataFixtures;

use App\Factory\ProprietaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProprietaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProprietaireFactory::createMany(5);

        $manager->flush();
    }
}
