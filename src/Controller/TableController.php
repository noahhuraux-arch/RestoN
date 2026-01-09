<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\TableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TableController extends AbstractController
{
    #[Route('/proprietaire/{restaurant}/tables', name: 'app_restaurant_tables')]
    #[IsGranted(new Expression('is_granted("ROLE_SERVEUR") or is_granted("ROLE_PROPRIETAIRE")'))]
    public function index(TableRepository $tableRepo, Restaurant $restaurant): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser() && !$restaurant->getServeurs()->contains($this->getUser())) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $tables = $tableRepo->findBy(['restaurant' => $restaurant], ['numero' => 'ASC']);

        return $this->render('table/index.html.twig', [
            'restaurant' => $restaurant,
            'tables' => $tables,
        ]);
    }
}
