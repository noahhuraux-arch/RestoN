<?php

namespace App\Controller;

use App\Entity\Serveur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ServeurController extends AbstractController
{
    #[Route('/serveur', name: 'app_serveur')]
    public function index(): Response
    {
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }

    #[Route('/{id}', name: 'app_serveur_show', requirements: ['id' => Requirement::DIGITS])]
    public function show(Serveur $serveur): Response
    {
        return $this->render('serveur/show.html.twig', [
            'serveur' => $serveur,
        ]);
    }
}
