<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Entity\Restaurant;
use App\Form\ProprietaireType;
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

        if (!$proprietaire) {
            return $this->redirectToRoute('app_login');
        }

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
}
