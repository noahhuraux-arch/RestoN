<?php
namespace App\Controller;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationController extends AbstractController
{
    /**
     * @param Request $request
     * @param TableRepository $tableRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/reserver', name: 'app_reservation_creer', methods: ['GET', 'POST'])]
    public function creerReservation(
        Request                $request,
        TableRepository        $tableRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tableDisponible = $tableRepository->findAvailableTables(
                $reservation->getDate(),
                $reservation->getHeure(),
                $reservation->getNbPers()
            );
        }
        return $this->render('reservation/creer.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
