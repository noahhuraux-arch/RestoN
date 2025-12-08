<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
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
            'Samedi' => ['ouvert' => true, 'plages' => '12:00 - 14:30 | 19:00 - 22:30'],
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

        return $this->render('home/index.html.twig', [
            'restaurant' => $infoRestaurant,
        ]);
    }
}
