<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Proprietaire;
use App\Entity\Restaurant;
use App\Entity\Table;
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

    #[Route('/{id}/{table}/commande/create', name: 'app_commande_create')]
    public function create(Restaurant $restaurant, Request $request, Table $table, EntityManagerInterface $em): Response
    {
        if ($this->getUser()->getRestaurant() !== $restaurant) {
            throw $this->createAccessDeniedException("Accès interdit");
        }

        if ($table->getRestaurant() !== $restaurant) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $user = $this->getUser();
        $commande = new Commande();
        $commande->setTables($table);
        $commande->setServeur($user);
        $form = $this->createForm(CommandeType::class, $commande, ['restaurant' => $restaurant]);
        $form->handleRequest($request);

        $produitsGroupes = [
            'Boissons' => [],
            'Entrées' => [],
            'Plats' => [],
            'Desserts' => [],
            'Menus' => [],
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
            'table' => $table,
            'serveur' => $user,
            'produitsGroupes' => array_filter($produitsGroupes),
        ]);
    }


}
