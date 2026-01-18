<?php

declare(strict_types=1);

namespace App\Tests\Controller\Reservation;

use App\Factory\ClientFactory;
use App\Factory\ProprietaireFactory;
use App\Factory\ReservationFactory;
use App\Factory\RestaurantFactory;
use App\Factory\TableFactory;
use App\Tests\Support\ControllerTester;

final class DeleteCest
{
    public function formShowsReservationDataBeforeDeleting(ControllerTester $I): void
    {
        $proprietaire = ProprietaireFactory::createOne(['roles' => ['ROLE_PROPRIETAIRE']])->_real();
        $restaurant = RestaurantFactory::createOne(['proprietaire' => $proprietaire]);
        $table = TableFactory::createOne(['restaurant' => $restaurant, 'numero' => 1]);
        $reservation = ReservationFactory::createOne([
            'restaurant' => $restaurant,
            'table' => $table,
            'numero' => 1,
            'client' => ClientFactory::createOne(),
        ]);

        $I->amLoggedInAs($proprietaire);
        $I->amOnPage("/{$restaurant->getId()}/reservation/{$reservation->getId()}/delete");

        $I->seeResponseCodeIsSuccessful();
        $I->see('Attention !', 'h1');
        $I->see('Supprimer', 'button');
        $I->see('Annuler', 'button');
    }
}
