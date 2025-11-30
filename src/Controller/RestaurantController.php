<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class RestaurantController extends AbstractController
{
    #[Route('/restaurant', name: 'app_restaurant')]
    public function index(): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'controller_name' => 'RestaurantController',
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

    #[Route('/restaurant/create', name: 'app_restaurant_create')]
    public function create(): Response
    {
        return $this->render('restaurant/create.html.twig');
    }

    #[Route('/restaurant/{id}/delete', name: 'app_restaurant_delete', requirements: ['id' => Requirement::DIGITS])]
    public function delete(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/delete.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }
}
