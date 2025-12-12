<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_PROPRIETAIRE')]
final class ProprietaireController extends AbstractController
{
    #[Route('/proprietaire', name: 'app_proprietaire', requirements: ['id' => Requirement::DIGITS])]
    public function index(): Response
    {
        $proprietaire = $this->getUser();

        if (!$proprietaire) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('proprietaire/liste.html.twig', [
            'proprietaire' => $proprietaire,
            'restaurants' => $proprietaire->getRestaurants(),
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
