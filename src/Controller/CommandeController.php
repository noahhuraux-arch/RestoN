<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Restaurant;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    #[Route('/{id}/commandes', name: 'app_commande_index')]
    public function index(Restaurant $restaurant, EntityManagerInterface $em): Response
    {
        $commandes = $em->getRepository(Commande::class)->findAll();
        return $this->render('commande/index.html.twig', [
            'restaurant' => $restaurant,
            'commandes' => $commandes,
        ]);
    }

    #[Route('/{id}/commande/create', name: 'app_commande_create')]
    public function create(Restaurant $restaurant, Request $request, EntityManagerInterface $em): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande, ['restaurant' => $restaurant]);
        $form->handleRequest($request);

        $produitsGroupes = [
            'Boissons' => [],
            'Menus' => [],
            'Entrées' => [],
            'Plats' => [],
            'Desserts' => [],
            'Autres' => [],
        ];

        $choices = $form->get('produits')->getConfig()->getAttribute('choice_list')->getChoices();

        foreach ($choices as $index => $produit) {
            $categorie = 'Autres';

            if ($produit instanceof Boisson) {
                $categorie = 'Boissons';
            } elseif ($produit instanceof Menu) {
                $categorie = 'Menus';
            } elseif ($produit instanceof Plat && $produit->getTypePlat()) {
                $categorie = $produit->getTypePlat()->getLib();
            }

            $produitsGroupes[$categorie][$index] = $produit;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $total = 0;
            foreach ($commande->getProduits() as $p) {
                $total += $p->getPrixProduit();
            }
            $commande->setPrixCommande($total);
            if (method_exists($commande, 'setDateCommande')) {
                $commande->setDateCommande(new \DateTime());
            }

            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('app_commande_index', ['id' => $restaurant->getId()]);
        }

        return $this->render('commande/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
            'produitsGroupes' => array_filter($produitsGroupes),
        ]);
    }


}
