<?php

namespace App\DataFixtures;

use App\Factory\ServeurFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServeurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ServeurFactory::createMany(10);
        $manager->flush();
    }
}
