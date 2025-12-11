<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantHorairesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HoraireController extends AbstractController
{
    #[Route('/proprietaire/restaurant/{id}/horaires', name: 'app_restaurant_horaires')]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function update(Request $request, Restaurant $restaurant, EntityManagerInterface $entityManager): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $form = $this->createForm(RestaurantHorairesType::class, $restaurant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_proprietaire_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('horaire/update.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form,
        ]);
    }
}
