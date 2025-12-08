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

        $horaireDuJour = null;
        $texteHoraire = "Non défini";

        foreach ($restaurant->getHoraires() as $horaire) {
            if ($horaire->getJour() === $nomJourActuel) {
                $horaireDuJour = $horaire;
                break;
            }
        }

        if ($horaireDuJour) {
            if ($horaireDuJour->isFerme()) {
                $texteHoraire = "Fermé";
            } else {
                $plages = [];
                if ($horaireDuJour->getOuvertureMidi() && $horaireDuJour->getFermetureMidi()) {
                    $plages[] = $horaireDuJour->getOuvertureMidi()->format('H:i') . ' - ' . $horaireDuJour->getFermetureMidi()->format('H:i');
                }
                if ($horaireDuJour->getOuvertureSoir() && $horaireDuJour->getFermetureSoir()) {
                    $plages[] = $horaireDuJour->getOuvertureSoir()->format('H:i') . ' - ' . $horaireDuJour->getFermetureSoir()->format('H:i');
                }
                if (empty($plages)) {
                    $texteHoraire = "Ouvert (Horaires non spécifiés)";
                } else {
                    $texteHoraire = implode(' | ', $plages);
                }
            }
        }

        return $this->render('restaurant/index.html.twig', [
            'restaurant' => $restaurant,
            'nom_jour_actuel' => $nomJourActuel,
            'texte_horaire_actuel' => $texteHoraire
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
