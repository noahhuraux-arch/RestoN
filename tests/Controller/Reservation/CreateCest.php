<?php

declare(strict_types=1);

namespace App\Tests\Controller\Reservation;

use App\Factory\ProprietaireFactory;
use App\Factory\RestaurantFactory;
use App\Tests\Support\ControllerTester;

final class CreateCest
{
    public function formShowsCreationFields(ControllerTester $I): void
    {
        $proprietaire = ProprietaireFactory::createOne();
        $restaurant = RestaurantFactory::createOne(['proprietaire' => $proprietaire]);

        $I->amOnPage("/{$restaurant->getId()}/reserver");

        $I->seeResponseCodeIsSuccessful();
        $I->see('Réserver', 'h1');
        $I->seeElement('form');
    }
}
