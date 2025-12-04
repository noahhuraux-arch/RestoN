<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Plat;
use App\Form\BoissonType;
use App\Form\PlatType;
use App\Repository\BoissonRepository;
use App\Repository\PlatRepository;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ProduitController extends AbstractController
{
    #[Route('/{idRestau}/carte', name: 'app_produit', requirements: ['idRestau' => Requirement::DIGITS])]
    public function listMenu(BoissonRepository $boissonRep, PlatRepository $platRep, RestaurantRepository $restauRepo, int $idRestau): Response
    {
        $restaurant = $restauRepo->find($idRestau);


        $boisson = $boissonRep->findBy(['idRestau' => $idRestau], ['alcoolise' => 'ASC', 'prixProduit' => 'ASC']);
        $produit = $platRep->findBy(['idRestau' => $idRestau], ['libProduit' => 'ASC', 'prixProduit' => 'ASC']);

        $entrees = [];
        $plat = [];
        $dessert = [];

        foreach ($produit as $prod) {
            $typeId = $prod->getTypePlat()->getId();
            if (1 === $typeId) {
                $entrees[] = $prod;
            } elseif (2 === $typeId) {
                $plat[] = $prod;
            } else {
                $dessert[] = $prod;
            }
        }

        return $this->render('produit/index.html.twig',
            ['boissons' => $boisson,
                'entrees' => $entrees,
                'plats' => $plat,
                'desserts' => $dessert]);
    }


    #[Route('/{idRestau}/boisson/create', name: 'app_produit_boisson_create', requirements: ['idRestau' => Requirement::DIGITS])]
    public function createBoisson(Request $request, EntityManagerInterface $entityManager, int $idRestau, RestaurantRepository $restoRepo):Response
    {
        $boisson = new Boisson();
        $form = $this->createForm(BoissonType::class, $boisson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $restaurant = $restoRepo->find($idRestau);
            $restaurant->addBoisson($boisson);

            $entityManager->persist($boisson);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit', ['idRestau' => $restaurant->getid()]);
        }

        return $this->render('produit/boisson/create.html.twig', [
            'form' => $form,
        ]);
    }



    #[Route('/{idRestau}/plat/create', name: 'app_produit_plat_create', requirements: ['idRestau' => Requirement::DIGITS])]
    public function createPlat(Request $request, EntityManagerInterface $entityManager, int $idRestau, RestaurantRepository $restoRepo):Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant = $restoRepo->find($idRestau);
            $restaurant->addPlat($plat);

            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit', ['idRestau' => $restaurant->getid()]);
        }

        return $this->render('produit/plat/create.html.twig', [
            'form' => $form,
        ]);
    }
}

