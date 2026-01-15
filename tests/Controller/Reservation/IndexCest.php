<?php

declare(strict_types=1);

namespace App\Tests\Controller\Reservation;

use App\Factory\ClientFactory;
use App\Factory\ProprietaireFactory;
use App\Factory\ReservationFactory;
use App\Factory\RestaurantFactory;
use App\Factory\TableFactory;
use App\Tests\Support\ControllerTester;

final class IndexCest
{
    public function reservationListContainsRightNumberOfElements(ControllerTester $I): void
    {
        $proprio = ProprietaireFactory::createOne(['roles' => ['ROLE_PROPRIETAIRE']])->_real();
        $resto = RestaurantFactory::createOne(['proprietaire' => $proprio]);
        $table = TableFactory::createOne(['restaurant' => $resto, 'numero' => 1]);

        ReservationFactory::createMany(5, [
            'restaurant' => $resto,
            'table' => $table,
            'numero' => 1,
            'client' => ClientFactory::createOne(),
        ]);

        $I->amLoggedInAs($proprio);
        $I->amOnPage("/{$resto->getId()}/reservation");

        $I->seeResponseCodeIs(200);
        $I->seeInTitle('Réservations - '.$resto->getLibRestau());
        $I->seeNumberOfElements('.card', 6);
    }

    public function firstReservationLinkLeadsToCorrectRoute(ControllerTester $I): void
    {
        $proprio = ProprietaireFactory::createOne(['roles' => ['ROLE_PROPRIETAIRE']])->_real();
        $resto = RestaurantFactory::createOne(['proprietaire' => $proprio]);
        $table = TableFactory::createOne(['restaurant' => $resto, 'numero' => 1]);

        ReservationFactory::createMany(5, [
            'restaurant' => $resto,
            'table' => $table,
            'numero' => 1,
            'client' => ClientFactory::createOne(),
        ]);
        $I->amLoggedInAs($proprio);
        $I->amOnPage('/'.$resto->getId().'/reservation');
        $I->seeResponseCodeIs(200);
        $I->click('Voir détails');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentRouteIs('app_reservation_show');
    }

    public function clickOnFirstReservationInList(ControllerTester $I): void
    {
        $proprio = ProprietaireFactory::createOne(['roles' => ['ROLE_PROPRIETAIRE']])->_real();
        $resto = RestaurantFactory::createOne(['proprietaire' => $proprio]);
        $table = TableFactory::createOne(['restaurant' => $resto, 'numero' => 1]);
        $clientJoe = ClientFactory::createOne([
            'prenom' => 'Joe',
            'nom' => 'Aaaaaaaaaaaaaaa',
        ]);

        ReservationFactory::createOne([
            'restaurant' => $resto,
            'table' => $table,
            'client' => $clientJoe,
            'numero' => 1,
        ]);
        ReservationFactory::createMany(5, [
            'restaurant' => $resto,
            'table' => $table,
            'client' => ClientFactory::new(),
            'numero' => 2,
        ]);

        $I->amLoggedInAs($proprio);
        $I->amOnPage('/'.$resto->getId().'/reservation');
        $I->click('Voir détails', '.card');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentRouteIs('app_reservation_show');
    }
}
