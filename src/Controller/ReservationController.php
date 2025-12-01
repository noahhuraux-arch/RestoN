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
        $erreurMessage= null;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tableDisponible = $tableRepository->findAvailableTables(
                $reservation->getDate(),
                $reservation->getHeure(),
                $reservation->getNbPers()
            );
            if ($tableDisponible === null) {
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
        ]);
    }

}
