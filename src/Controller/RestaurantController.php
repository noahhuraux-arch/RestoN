<?php

namespace App\Controller;

use App\Entity\Horaire;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\ProprietaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class RestaurantController extends AbstractController
{
    #[Route('/{id}', name: 'app_restaurant_home', requirements: ['id' => Requirement::DIGITS])]
    public function showHome(int $id, Restaurant $restaurant): Response
    {
        $joursSemaine = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];
        $nomJourActuel = $joursSemaine[date('l')];

        $horaires = [
            'Lundi' => ['ouvert' => false, 'plages' => 'Fermé'],
            'Mardi' => ['ouvert' => true, 'plages' => '12:00 - 14:30 | 19:00 - 22:30'],
            'Mercredi' => ['ouvert' => true, 'plages' => '12:00 - 14:30 | 19:00 - 22:30'],
            'Jeudi' => ['ouvert' => true, 'plages' => '12:00 - 14:30 | 19:00 - 22:30'],
            'Vendredi' => ['ouvert' => true, 'plages' => '12:00 - 14:30 | 19:00 - 22:30'],
            'Samedi' => ['ouvert' => true, 'plages' => '12:00 - 14:30 | 19:00 - 22h30'],
            'Dimanche' => ['ouvert' => true, 'plages' => '12:00 - 15:00'],
        ];

        $horaireDuJour = $horaires[$nomJourActuel] ?? ['ouvert' => false, 'plages' => 'Non défini'];
        $affichageHoraire = $horaireDuJour['plages'];

        $infoRestaurant = [
            'adresse' => '12 Avenue de la Gastronomie, 75001 Paris',
            'telephone' => '01 23 45 67 89',
            'email' => 'contact@reston.fr',
            'nom_jour' => $nomJourActuel,
            'horaire_jour' => $affichageHoraire,
        ];

        return $this->render('restaurant/index.html.twig', [
            'restaurant' => $restaurant,
            'restaurant1' => $infoRestaurant,
        ]);
    }

    #[Route('/restaurant/{id}/update', name: 'app_restaurant_update', requirements: ['id' => Requirement::DIGITS])]
    public function update(Request $request, Restaurant $restaurant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_proprietaire_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('restaurant/update.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form,
        ]);
    }

    #[Route('/proprietaire/{idProprio}/restaurant/create', name: 'app_restaurant_create', requirements: ['idProprio' => Requirement::DIGITS])]
    public function create(Request $request, EntityManagerInterface $entityManager, ProprietaireRepository $proprietaireRepo, int $idProprio): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proprietaire = $proprietaireRepo->find($idProprio);
            $restaurant->setProprietaire($proprietaire);

            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

            foreach ($jours as $jour) {
                $horaire = new Horaire();
                $horaire->setJour($jour);
                $horaire->setFerme(true);
                $horaire->setRestaurant($restaurant);

                $entityManager->persist($horaire);
            }

            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('app_proprietaire_show', ['id' => $restaurant->getid()]);
        }

        return $this->render('restaurant/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/restaurant/{id}/delete', name: 'app_restaurant_delete')]
    public function delete(Request $request, Restaurant $restaurant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class)
            ->add('cancel', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $idProprio = $restaurant->getProprietaire()->getId();

                $entityManager->remove($restaurant);
                $entityManager->flush();

                return $this->redirectToRoute('app_proprietaire', ['id' => $idProprio]);
            }

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('app_proprietaire_show', ['id' => $restaurant->getId()]);
            }
        }

        return $this->render('restaurant/delete.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }
}
