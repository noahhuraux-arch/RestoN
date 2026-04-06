<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Form\ReservationType;
use App\Repository\ClientRepository;
use App\Repository\ReservationRepository;
use App\Repository\TableRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationController extends AbstractController
{
    #[Route('{id}/reserver', name: 'app_reservation_create', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function createReservation(Request $request, Restaurant $restaurant, ReservationRepository $restoRepo, TableRepository $tableRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas la permission de créer des réservations dans un restaurant qui ne vous appartient pas");
        }

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
            $nbReservations = $restoRepo->count(['restaurant' => $restaurant]);
            $reservation->setNumero($nbReservations + 1);
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

            $emailService->sendReservationConfirmation($reservation);

            $logo = $restaurant->getLogo() ? '/images/logos/'.$restaurant->getLogo() : '/images/favicon.png';
            $style = $restaurant->getLogo() ? 'object-fit: cover;' : '';
            $class = $restaurant->getLogo() ? 'rounded-circle' : '';

            $message = sprintf(
                '<div class="d-flex align-items-center">
                    <img src="%s" alt="Logo" width="50" height="50" class="me-3 %s" style="%s">
                    <div>
                        <h5 class="alert-heading fw-bold mb-1">%s</h5>
                        <span>Votre réservation est confirmée ! <strong>Veuillez consulter vos emails</strong> pour le récapitulatif.</span>
                    </div>
                </div>',
                $logo, $class, $style, $restaurant->getLibRestau()
            );

            $this->addFlash('success', $message);

            return $this->redirectToRoute('app_restaurant_home', ['id' => $restaurant->getId()]);
        }

        return $this->render('reservation/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/{id}/reservation/{idReservation}/update', name: 'app_reservation_update', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function updateReservation(Request $request, Restaurant $restaurant, #[MapEntity(mapping: ['idReservation' => 'id'])] Reservation $reservation, ReservationRepository $restoRepo, TableRepository $tableRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas la permission de créer des réservations dans un restaurant qui ne vous appartient pas");
        }

        $form = $this->createForm(ReservationType::class, $reservation, ['restaurant' => $restaurant]);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            return $this->render('reservation/update.html.twig', [
                'form' => $form->createView(),
                'restaurant' => $restaurant,
                'reservation' => $reservation,
            ]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setRestaurant($restaurant);
            $NouveauClient = $reservation->getClient();
            $nbReservations = $restoRepo->count(['restaurant' => $restaurant]);
            $reservation->setNumero($nbReservations + 1);
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

            $emailService->sendReservationConfirmation($reservation);

            $logo = $restaurant->getLogo() ? '/images/logos/'.$restaurant->getLogo() : '/images/favicon.png';
            $style = $restaurant->getLogo() ? 'object-fit: cover;' : '';
            $class = $restaurant->getLogo() ? 'rounded-circle' : '';

            $message = sprintf(
                '<div class="d-flex align-items-center">
                    <img src="%s" alt="Logo" width="50" height="50" class="me-3 %s" style="%s">
                    <div>
                        <h5 class="alert-heading fw-bold mb-1">%s</h5>
                        <span>Votre réservation est confirmée ! <strong>Veuillez consulter vos emails</strong> pour le récapitulatif.</span>
                    </div>
                </div>',
                $logo, $class, $style, $restaurant->getLibRestau()
            );

            $this->addFlash('success', $message);

            return $this->redirectToRoute('app_restaurant_home', ['id' => $restaurant->getId()]);
        }

        return $this->render('reservation/update.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
            'reservation' => $reservation,
        ]);
    }

    #[IsGranted(new Expression('is_granted("ROLE_SERVEUR") or is_granted("ROLE_PROPRIETAIRE")'))]
    #[Route('{id}/reservation', name: 'app_reservation')]
    public function indexReservation(Request $request, Restaurant $restaurant, ReservationRepository $reservationRepository, #[MapQueryParameter] string $searchText = ''): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser() && !$restaurant->getServeurs()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }
        $filter = $request->query->get('filter');
        $order = $request->query->get('order', 'desc');

        if ('today' === $filter) {
            $reservations = $reservationRepository->findTodayByReservation($restaurant);
        } elseif ('' !== $searchText) {
            $reservations = $reservationRepository->searchByClientName($restaurant, $searchText);
        } else {
            $reservations = $reservationRepository->findByRestaurantWithDetailsReservations($restaurant);
        }

        usort($reservations, function ($a, $b) use ($order) {
            $dateA = $a->getDate()->format('Y-m-d') . ' ' . $a->getHeure()->format('H:i');
            $dateB = $b->getDate()->format('Y-m-d') . ' ' . $b->getHeure()->format('H:i');

            if ($order === 'asc') {
                return strcmp($dateA, $dateB);
            }

            return strcmp($dateB, $dateA);
        });

        return $this->render('reservation/index.html.twig', [
            'restaurant' => $restaurant,
            'reservations' => $reservations,
            'order' => $order,
        ]);
    }

    #[IsGranted(new Expression('is_granted("ROLE_SERVEUR") or is_granted("ROLE_PROPRIETAIRE")'))]
    #[Route('{id}/reservation/{idReservation}', name: 'app_reservation_show')]
    public function showReservation(Restaurant $restaurant, #[MapEntity(mapping: ['idReservation' => 'id'])] Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findFullDetailsReservation($reservation->getId());
        if ($restaurant->getProprietaire() !== $this->getUser() && !$restaurant->getServeurs()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        return $this->render('reservation/show.html.twig', [
            'restaurant' => $restaurant,
            'reservations' => $reservation,
        ]);
    }

    #[Route('{id}/reservation/{idReservation}/delete', name: 'app_reservation_delete')]
    #[IsGranted(new Expression('is_granted("ROLE_SERVEUR") or is_granted("ROLE_PROPRIETAIRE")'))]
    public function deleteReservation(Request $request, #[MapEntity(mapping: ['idReservation' => 'id'])] Reservation $reservation, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->findFullDetailsReservation($reservation->getId());
        $restaurant = $reservation->getRestaurant();
        if ($restaurant->getProprietaire() !== $this->getUser() && !$restaurant->getServeurs()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

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
                return $this->redirectToRoute('app_reservation_show', ['id' => $restaurant->getId(), 'idReservation' => $reservation->getId()]);
            }
        }

        return $this->render('reservation/delete.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/reservation/{reservation}/installer', name: 'app_reservation_installer')]
    public function installer(Reservation $reservation, EntityManagerInterface $em): Response
    {
        $restaurant = $reservation->getRestaurant();
        $reservation->setStatus('Occupee');
        $table = $reservation->getTable();
        if ($table) {
            $table->setDisponible(false);
        }
        $em->flush();

        return $this->redirectToRoute('app_reservation_show', [
            'id' => $reservation->getRestaurant()->getId(),
            'idReservation' => $reservation->getId(),
        ]);
    }

    #[Route('/reservation/{reservation}/liberer', name: 'app_reservation_liberer')]
    public function liberer(Reservation $reservation, EntityManagerInterface $em): Response
    {
        $restaurant = $reservation->getRestaurant();
        $reservation->setStatus('Terminee');
        $table = $reservation->getTable();
        if ($table) {
            $table->setDisponible(true);
        }
        $em->flush();

        return $this->redirectToRoute('app_reservation', ['id' => $reservation->getRestaurant()->getId()]);
    }
}
