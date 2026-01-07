<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ServeurController extends AbstractController
{
    #[Route('/{id}/serveur', name: 'app_serveur_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(
        Restaurant $restaurant,
        ServeurRepository $serveurRepository,
    ): Response {
        $serveurs = $serveurRepository->findBy(['restaurant' => $restaurant]);

        return $this->render('serveur/show.html.twig', [
            'restaurant' => $restaurant,
            'serveurs' => $serveurs,
        ]);
    }

    #[Route('/{id}/serveur/create', name: 'app_serveur_create', requirements: ['id' => Requirement::DIGITS])]
    public function create(Restaurant $restaurant, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $serveur = new Serveur();
        $form = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $serveur->setRestaurant($restaurant);
            $serveur->setRoles(['ROLE_SERVEUR']);
            $serveur->setMustChangePassword(true);

            $tempPass = 'Bienvenue2026!';
            $serveur->setPassword($hasher->hashPassword($serveur, $tempPass));

            $em->persist($serveur);
            $em->flush();

            $this->addFlash('success', 'Le serveur a été créé. Mot de passe provisoire : '.$tempPass);

            return $this->redirectToRoute('app_serveur_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('serveur/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }
}
