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
    #[Route('{id}/reserver', name: 'app_reservation_creer', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function creerReservation(Request $request, Restaurant $restaurant, TableRepository $tableRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $erreurMessage = null;

        $form->handleRequest($request);
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
            if (null === $tableDisponible) {
                $erreurMessage = 'Désolé, ce créneau n\'est plus disponible.';
            } else {
                $reservation->setTable($tableDisponible);
                $entityManager->persist($reservation);
                $entityManager->flush();

                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('reservation/creer.html.twig', [
            'form' => $form->createView(),
            'erreur' => $erreurMessage,
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('{id}/disponibilites/', name: 'api_disponibilites', methods: ['GET'])]
    public function apiDisponibilites(Restaurant $restaurant, Request $request, TableRepository $tableRepo): Response
    {
        $date = new \DateTime($request->query->get('date'));
        $nb = (int) $request->query->get('nbPers');
        $creneaux = ['12:00', '13:00', '14:00', '19:00', '20:00', '21:00', '22:00'];
        $dispos = [];

        foreach ($creneaux as $horaire) {
            $heure = new \DateTime($horaire);
            if ($tableRepo->findAvailableTables($restaurant, $date, $heure, $nb)) {
                $dispos[] = $horaire;
            }
        }
        return $this->json($dispos);
    }
}
