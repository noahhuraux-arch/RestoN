<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Form\ReservationType;
use App\Repository\ClientRepository;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            $Client = $clientRepository->findExistingByClient($NouveauClient->getEmail());
            $tableDisponible = $tableRepository->findAvailableTables(
                $restaurant,
                $reservation->getDate(),
                $reservation->getHeure(),
                $reservation->getNbPers()
            );
            if ($Client) {
                $Client->setRoles(['ROLE_CLIENT']);
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
    #[IsGranted('ROLE_SERVEUR')]
    #[Route('{id}/reservation', name: 'app_reservation')]
    public function indexReservation(Restaurant $restaurant): Response
    {
        $reservation = $restaurant->getReservations();

        return $this->render('reservation/index.html.twig', [
            'restaurant' => $restaurant,
            'reservation' => $reservation,
        ]);
    }

    #[IsGranted('ROLE_SERVEUR')]
    #[Route('{id}/reservation/{idReservation}', name: 'app_reservation_show')]
    public function showReservation(Restaurant $restaurant, #[MapEntity(mapping: ['idReservation' => 'id'])] Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'restaurant' => $restaurant,
            'reservation' => $reservation,
        ]);
    }
    #[Route('{id}/reservation/{idReservation}/delete', name: 'app_reservation_delete')]
    #[IsGranted('ROLE_SERVEUR')]
    public function deleteReservation(Request $request, #[MapEntity(mapping: ['idReservation' => 'id'])] Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $restaurant = $reservation->getRestaurant();
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class)
            ->add('cancel', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($reservation);
                $entityManager->flush();

                return $this->redirectToRoute('app_reservation', ['id' => $restaurant->getId()]);
            }

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('app_reservation_show', ['id' => $restaurant->getId(),'idReservation' => $reservation->getId()]);
            }
        }

        return $this->render('reservation/delete.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);

    }

}
