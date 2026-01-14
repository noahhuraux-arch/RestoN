<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Entity\Restaurant;
use App\Form\ProprietaireType;
use App\Repository\CommandeRepository;
use App\Repository\ReservationRepository;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProprietaireController extends AbstractController
{
    #[IsGranted('ROLE_PROPRIETAIRE')]
    #[Route('/proprietaire', name: 'app_proprietaire', requirements: ['id' => Requirement::DIGITS])]
    public function index(): Response
    {
        $proprietaire = $this->getUser();

        return $this->render('proprietaire/liste.html.twig', [
            'proprietaire' => $proprietaire,
            'restaurants' => $proprietaire->getRestaurants(),
        ]);
    }

    #[IsGranted('ROLE_PROPRIETAIRE')]
    #[Route('/proprietaire/restaurant/{id}', name: 'app_proprietaire_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(Restaurant $restaurant): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        return $this->render('proprietaire/index.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/register', name: 'app_proprietaire_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(['ROLE_PROPRIETAIRE']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_PROPRIETAIRE')]
    #[Route('/proprietaire/update', name: 'app_proprietaire_update')]
    public function update(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $proprietaire = $this->getUser();

        $form = $this->createForm(ProprietaireType::class, $proprietaire, [
            'is_edit' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();

            if (!empty($plainPassword)) {
                $proprietaire->setPassword($hasher->hashPassword($proprietaire, $plainPassword));
            }

            $em->flush();

            return $this->redirectToRoute('app_proprietaire');
        }

        return $this->render('proprietaire/update.html.twig', ['form' => $form->createView()]);
    }

    #[IsGranted('ROLE_PROPRIETAIRE')]
    #[Route('/proprietaire/restaurant/{id}/dashboard', name: 'app_proprietaire_dashbord')]
    public function dashboard(Restaurant $restaurant, ReservationRepository $reservationRepository, CommandeRepository $commandeRepository, ServeurRepository $serveurRepository): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $Statistique = [
            'TotalReservation' => $reservationRepository->CountTotalByRestaurantReservations($restaurant),
            'TotalCommande' => $commandeRepository->CountTotalByRestaurantCommandes($restaurant),
            'SumCommande' => $commandeRepository->SumTotalByRestaurantCommandes($restaurant),
            'TotalServeur' => $serveurRepository->CountTotalByRestaurant($restaurant),
            'TotalChargeEmploye' => $serveurRepository->SumTotalByRestaurantServeur($restaurant),
        ];

        return $this->render('proprietaire/dashboard.html.twig', [
            'restaurant' => $restaurant,
            'stats' => $Statistique,
        ]);
    }
}
