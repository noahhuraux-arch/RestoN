<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ProprietaireController extends AbstractController
{
    #[Route('/proprietaire', name: 'app_proprietaire')]
    public function index(): Response
    {
        return $this->render('proprietaire/index.html.twig', [
            'controller_name' => 'ProprietaireController',
        ]);
    }

    #[Route('/proprietaire/restaurant/{id}', name: 'app_proprietaire_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('proprietaire/index.html.twig', [
            'restaurant' => $restaurant,
        ]);

    }
}
