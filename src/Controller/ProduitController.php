<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Form\BoissonType;
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
    #[Route('/carte', name: 'app_produit')]
    public function listMenu(BoissonRepository $boissonRep, PlatRepository $platRep): Response
    {
        $boisson = $boissonRep->findBy([], ['alcoolise' => 'ASC', 'prixProduit' => 'ASC']);
        $produit = $platRep->findAll();

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


    #[Route('/restaurant/{idRestau}/produit/boisson/create', name: 'app_produit_boisson_create', requirements: ['idRestau' => Requirement::DIGITS])]
    public function create(Request $request, EntityManagerInterface $entityManager, int $idRestau, RestaurantRepository $restoRepo)
    {
        $boisson = new Boisson();
        $form = $this->createForm(BoissonType::class, $boisson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $restaurant = $restoRepo->find($idRestau);
            $restaurant->addBoisson($boisson);

            $entityManager->persist($boisson);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/createBoisson.html.twig', [
            'form' => $form,
        ]);
    }
}

