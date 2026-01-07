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
    #[Route('/{id}/commande/create', name: 'app_commande_create')]
    public function create(Restaurant $restaurant, Request $request, EntityManagerInterface $em): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande, ['restaurant' => $restaurant]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $total = 0;
            foreach ($commande->getProduits() as $produit) {
                $total += $produit->getPrixProduit();
            }
            $commande->setPrixCommande($total);

            $resData = $form->get('reservation')->getData();
            if ($resData && $resData->getTable()) {
                $commande->setTables($resData->getTable());
            }

            $em->persist($commande);
            $em->flush();

            $this->addFlash('success', 'Commande validée !');
            return $this->redirectToRoute('app_serveur_show', ['id' => $restaurant->getId()]);
        }

        return $this->render('commande/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
        ]);
    }
}
