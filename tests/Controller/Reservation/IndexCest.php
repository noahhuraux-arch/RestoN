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

    public function searchFormCanBeFilledAndSent(ControllerTester $I): void
    {
        $proprio = ProprietaireFactory::createOne(['roles' => ['ROLE_PROPRIETAIRE']])->_real();
        $resto = RestaurantFactory::createOne(['proprietaire' => $proprio]);
        $I->amLoggedInAs($proprio);
        $I->amOnPage('/'.$resto->getId().'/reservation');
        $I->seeResponseCodeIsSuccessful();
        $I->fillField('searchText', 'Joe');
        $I->click('Recherche');
        $I->seeCurrentRouteIs('app_reservation', ['id' => $resto->getId()]);
        $I->seeInCurrentUrl('searchText=Joe');
    }

    public function VerifiesReservationsAreSortedByDateAndTime(ControllerTester $I): void
    {
        $proprio = ProprietaireFactory::createOne(['roles' => ['ROLE_PROPRIETAIRE']])->_real();
        $resto = RestaurantFactory::createOne(['proprietaire' => $proprio]);
        $table = TableFactory::createOne(['restaurant' => $resto, 'numero' => 1]);
        $client = ClientFactory::createOne();

        ReservationFactory::createSequence([
            [
                'restaurant' => $resto,
                'table' => $table,
                'client' => $client,
                'numero' => 1,
                'date' => new \DateTime('2026-02-10'),
                'heure' => new \DateTime('19:30:00'),
                'nbPers' => 2,
            ],
            [
                'restaurant' => $resto,
                'table' => $table,
                'client' => $client,
                'numero' => 2,
                'date' => new \DateTime('2026-01-15'),
                'heure' => new \DateTime('20:00:00'),
                'nbPers' => 4,
            ],
            [
                'restaurant' => $resto,
                'table' => $table,
                'client' => $client,
                'numero' => 3,
                'date' => new \DateTime('2026-01-15'),
                'heure' => new \DateTime('12:00:00'),
                'nbPers' => 2,
            ],
        ]);
        $I->amLoggedInAs($proprio);
        $I->amOnPage('/'.$resto->getId().'/reservation');
        $dates = $I->grabMultiple('.bi-calendar3');
        $heures = $I->grabMultiple('.badge.bg-dark');
        $actualList = [];
        for ($i = 0; $i < count($dates); ++$i) {
            $actualList[] = $dates[$i].' '.$heures[$i];
        }
        $expected = [
            ' 15/01/2026 12:00',
            ' 15/01/2026 20:00',
            ' 10/02/2026 19:30',
        ];

        $I->assertEquals($expected, $actualList);
    }
}
