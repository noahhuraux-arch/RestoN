<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Plat;
use App\Entity\Produit;
use App\Entity\Restaurant;
use App\Form\BoissonType;
use App\Form\PlatType;
use App\Repository\BoissonRepository;
use App\Repository\PlatRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{idRestau}/carte/proprietaire', name: 'app_produit_proprietaire', requirements: ['idRestau' => Requirement::DIGITS])]
    public function listMenuProprietaire(BoissonRepository $boissonRep, PlatRepository $platRep, RestaurantRepository $restauRepo, int $idRestau): Response
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

        return $this->render('produit/proprietaire/carte.html.twig',
            ['boissons' => $boisson,
                'entrees' => $entrees,
                'plats' => $plat,
                'desserts' => $dessert,
                'restaurant' => $restaurant]);
    }

    #[Route('/produit/{id}/', name: 'app_produit_details', requirements: ['id' => Requirement::DIGITS])]
    public function Produit(Produit $produit): Response
    {
        return $this->render('produit/produit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{idRestau}/boisson/create', name: 'app_produit_boisson_create', requirements: ['idRestau' => Requirement::DIGITS])]
    public function createBoisson(Request $request, EntityManagerInterface $entityManager, int $idRestau, RestaurantRepository $restoRepo): Response
    {
        $restaurant = $restoRepo->find($idRestau);
        $boisson = new Boisson();
        $form = $this->createForm(BoissonType::class, $boisson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant->addBoisson($boisson);

            $entityManager->persist($boisson);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_proprio', ['idRestau' => $restaurant->getid()]);
        }

        return $this->render('produit/boisson/create.html.twig', [
            'form' => $form,
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('{idRestau}/boisson/{idBoisson}/update', name: 'app_produit_boisson_update', requirements: ['idRestau' => Requirement::DIGITS, 'idBoisson' => Requirement::DIGITS])]
    public function updateBoisson(Request $request, Boisson $idBoisson, BoissonRepository $boissonRepo, int $idRestau, EntityManagerInterface $entityManager): Response
    {
        $restaurant = $entityManager->getRepository(Restaurant::class)->find($idRestau);

        $form = $this->createForm(BoissonType::class, $idBoisson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_details', ['id' => $idBoisson->getId()]);
        }

        return $this->render('produit/boisson/update.html.twig', [
            'boisson' => $idBoisson,
            'form' => $form,
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/{idRestau}/plat/create', name: 'app_produit_plat_create', requirements: ['idRestau' => Requirement::DIGITS])]
    public function createPlat(Request $request, EntityManagerInterface $entityManager, int $idRestau, RestaurantRepository $restoRepo): Response
    {
        $restaurant = $restoRepo->find($idRestau);

        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant->addPlat($plat);

            $entityManager->persist($plat);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_proprio', ['idRestau' => $restaurant->getid()]);
        }

        return $this->render('produit/plat/create.html.twig', [
            'form' => $form,
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('{idRestau}/plat/{idPlat}/update', name: 'app_produit_plat_update', requirements: ['idRestau' => Requirement::DIGITS, 'idPlat' => Requirement::DIGITS])]
    public function updatePlat(Request $request, Plat $idPlat, PlatRepository $platRepo, int $idRestau, EntityManagerInterface $entityManager): Response
    {
        $restaurant = $entityManager->getRepository(Restaurant::class)->find($idRestau);
        $form = $this->createForm(PlatType::class, $idPlat);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_details', ['id' => $idPlat->getId()]);
        }

        return $this->render('produit/plat/update.html.twig', [
            'plat' => $idPlat,
            'form' => $form,
            'restaurant' => $restaurant,
        ]);
    }

    #[Route('/produit/{id}/delete', name: 'app_produit_delete')]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $idRestau = $produit->getIdRestau()->getId();

        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class)
            ->add('cancel', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($produit);
                $entityManager->flush();

                return $this->redirectToRoute('app_produit_proprio', ['idRestau' => $idRestau]);
            }

            if ($form->get('cancel')->isClicked()) {
                return $this->redirectToRoute('app_produit_details', ['id' => $produit->getId()]);
            }
        }

        return $this->render('produit/delete.html.twig', [
            'produit' => $produit,
            'idRestau' => $idRestau,
            'form' => $form->createView(),
        ]);
    }
}
