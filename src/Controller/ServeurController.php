<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Serveur;
use App\Form\ServeurType;
use App\Repository\ServeurRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_PROPRIETAIRE')]
final class ServeurController extends AbstractController
{
    #[Route('/{id}/serveur', name: 'app_serveur_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(
        Restaurant $restaurant,
        ServeurRepository $serveurRepository,
    ): Response {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas accès aux serveurs de ce restaurant.");
        }

        $serveurs = $serveurRepository->findBy(['restaurant' => $restaurant]);

        return $this->render('serveur/show.html.twig', [
            'restaurant' => $restaurant,
            'serveurs' => $serveurs,
        ]);
    }

    #[Route('/{id}/serveur/create', name: 'app_serveur_create', requirements: ['id' => Requirement::DIGITS])]
    public function create(Restaurant $restaurant, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher, EmailService $emailService): Response
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
            $tempPass = bin2hex(random_bytes(4));

            $serveur->setPassword($hasher->hashPassword($serveur, $tempPass));

            $em->persist($serveur);
            $em->flush();

            $emailService->sendWelcomeServeur(
                $serveur->getEmail(),
                $serveur->getPrenom(),
                $tempPass,
                $restaurant
            );

            return $this->redirectToRoute('app_serveur_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('serveur/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/{restaurant}/serveur/{serveur}/delete', name: 'app_serveur_delete', requirements: ['serveur_id' => Requirement::DIGITS, 'restaurant_id' => Requirement::DIGITS])]
    public function delete(Request $request, Restaurant $restaurant, Serveur $serveur, EntityManagerInterface $entityManager): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser() || $serveur->getRestaurant() !== $restaurant) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, ['label' => 'Supprimer'])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($serveur);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_serveur_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('serveur/delete.html.twig', [
            'restaurant' => $restaurant,
            'serveur' => $serveur,
            'form' => $form->createView(),
        ]);
    }
}
