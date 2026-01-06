<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Form\ReservationType;
use App\Repository\ClientRepository;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ReservationController extends AbstractController
{
    #[Route('{id}/reserver', name: 'app_reservation_create', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function createReservation(Request $request, Restaurant $restaurant, TableRepository $tableRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation, ['restaurant' => $restaurant]);

        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            return $this->render('reservation/create.html.twig', [
                'form' => $form->createView(),
                'restaurant' => $restaurant,
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setRestaurant($restaurant);
            $NouveauClient = $reservation->getClient();
            $Client = $clientRepository->findExistingByClient($NouveauClient->getNom(), $NouveauClient->getPrenom(), $NouveauClient->getEmail(), $NouveauClient->getTelephone());
            $tableDisponible = $tableRepository->findAvailableTables(
                $restaurant,
                $reservation->getDate(),
                $reservation->getHeure(),
                $reservation->getNbPers()
            );
            if ($Client) {
                $reservation->setClient($Client);
            }
            $reservation->setTable($tableDisponible);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('reservation/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }
}
