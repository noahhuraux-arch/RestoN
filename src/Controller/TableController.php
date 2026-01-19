<?php

namespace App\Controller;

use App\Entity\Table;
use App\Entity\Restaurant;
use App\Form\TableType;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
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

        $tables = $tableRepo->findRestaurantWithReservations($restaurant);

        return $this->render('table/index.html.twig', [
            'restaurant' => $restaurant,
            'tables' => $tables,
        ]);
    }

    #[Route('/proprietaire/{restaurant}/tables/create', name: 'app_table_create')]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function create(Request $request, Restaurant $restaurant, EntityManagerInterface $em): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $table = new Table();
        $table->setRestaurant($restaurant);
        $table->setDisponible(true);

        $form = $this->createForm(TableType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($table);
            $em->flush();

            $this->addFlash('success', 'La table a été ajoutée avec succès.');
            return $this->redirectToRoute('app_restaurant_tables', ['restaurant' => $restaurant->getId()]);
        }

        return $this->render('table/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/proprietaire/{restaurant}/tables/{id}/delete', name: 'app_table_delete', methods: ['POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function delete(Request $request, Restaurant $restaurant, Table $table, EntityManagerInterface $em): Response
    {
        if ($restaurant->getProprietaire() !== $this->getUser() || $table->getRestaurant() !== $restaurant) {
            throw $this->createAccessDeniedException('Accès interdit');
        }


        if ($this->isCsrfTokenValid('delete'.$table->getId(), $request->request->get('_token'))) {
            if (!$table->isDisponible()) {
                $this->addFlash('danger', 'Impossible de supprimer une table occupée.');
            } else {
                $em->remove($table);
                $em->flush();
                $this->addFlash('success', 'La table a été supprimée.');
            }
        }

        return $this->redirectToRoute('app_restaurant_tables', ['restaurant' => $restaurant->getId()]);
    }
}
