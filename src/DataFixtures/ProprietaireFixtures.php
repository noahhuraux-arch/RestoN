<?php

namespace App\DataFixtures;

use App\Factory\ProprietaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProprietaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProprietaireFactory::createOne([
            'email' => 'root@example.com',
            'password' => 'test',
            'nom' => 'Dupont',
            'prenom' => 'Albert',
            'roles' => ['ROLE_PROPRIETAIRE'],
        ]);

        ProprietaireFactory::createMany(5);

        $manager->flush();
    }
}
