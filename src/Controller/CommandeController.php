<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Form\CommandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression('is_granted("ROLE_SERVEUR") or is_granted("ROLE_PROPRIETAIRE")'))]
class CommandeController extends AbstractController
{
    #[Route('/{id}/commandes', name: 'app_commande_index')]
    public function index(Restaurant $restaurant, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $isProprio = ($restaurant->getProprietaire() === $user);
        $isServeurDuResto = $restaurant->getServeurs()->contains($user);

        if (!$isProprio && !$isServeurDuResto) {
            throw $this->createAccessDeniedException('Accès interdit');
        }
        $reservations = $restaurant->getReservations();

        $commandes = $restaurant->getCommandes();

        return $this->render('commande/index.html.twig', [
            'restaurant' => $restaurant,
            'commandes' => $commandes,
            'etats' => \App\Enum\EnumEtatCommande::cases(),
        ]);
    }

    #[Route('/{id}/{table}/commande/create', name: 'app_commande_create')]
    public function create(Restaurant $restaurant, Request $request, Table $table, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $isProprio = ($restaurant->getProprietaire() === $user);
        $isServeurDuResto = $restaurant->getServeurs()->contains($user);
        if (!$isProprio && !$isServeurDuResto) {
            throw $this->createAccessDeniedException('Accès interdit');
        }
        if ($table->getRestaurant() !== $restaurant) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $commande = $em->getRepository(Commande::class)->findOneBy([
            'tables' => $table,
            'restaurant' => $restaurant,
            'isPaye' => false,
        ]);

        if (!$commande) {
            $user = $this->getUser();
            $commande = new Commande();
            $commande->setTables($table);
            $commande->setRestaurant($restaurant);

            if ($isServeurDuResto) {
                $commande->setServeur($user);
            } else {
                $commande->setServeur(null);
            }

            if ($table->isDisponible()) {
                $table->setDisponible(false);
            }
        }

        $form = $this->createForm(CommandeType::class, $commande, ['restaurant' => $restaurant]);
        $form->handleRequest($request);

        $produitsGroupes = [
            'Entrées' => [],
            'Plats' => [],
            'Desserts' => [],
            'Boissons' => [],
            'Menus' => [],
        ];

        $choices = $form->get('produits')->getConfig()->getAttribute('choice_list')->getChoices();

        foreach ($choices as $index => $produit) {
            $categorie = null;
            if ($produit instanceof Boisson) {
                $categorie = 'Boissons';
            } elseif ($produit instanceof Menu) {
                $categorie = 'Menus';
            } elseif ($produit instanceof Plat) {
                $typePlat = $produit->getTypePlat()->getLib();
                if ('Entrée' === $typePlat) {
                    $categorie = 'Entrées';
                } elseif ('Plat' === $typePlat) {
                    $categorie = 'Plats';
                } elseif ('Dessert' === $typePlat) {
                    $categorie = 'Desserts';
                }
            }
            if ($categorie) {
                $produitsGroupes[$categorie][$index] = $produit;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $qtys = $request->request->all('qtys');
            $total = 0;
            foreach ($commande->getProduits() as $produit) {
                $qte = isset($qtys[$produit->getId()]) ? (int) $qtys[$produit->getId()] : 1;
                $total += ($produit->getPrixProduit() * $qte);
                for ($i = 1; $i < $qte; ++$i) {
                    $commande->addProduit($produit);
                }
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
            'produitsGroupes' => array_filter($produitsGroupes), ]);
    }

    #[Route('/{id}/commandes/{commande}/payer', name: 'app_commande_payer')]
    public function payer(Restaurant $restaurant, Commande $commande, EntityManagerInterface $em): Response
    {
        $commande->setIsPaye(true);
        $table = $commande->getTables();
        if ($table) {
            $table->setDisponible(true);
            foreach ($table->getReservations() as $reservation) {
                if ('Occupee' === $reservation->getStatus()) {
                    $reservation->setStatus('Terminee');
                }
            }
        }
        $em->flush();

        return $this->redirectToRoute('app_commande_index', ['id' => $restaurant->getId()]);
    }

    #[Route('/{id}/commande/{commandeId}/update', name: 'app_commande_update')]
    public function update(Restaurant $restaurant, int $commandeId, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $commande = $em->getRepository(Commande::class)->find($commandeId);

        if (!$commande || $commande->getRestaurant() !== $restaurant) {
            throw $this->createNotFoundException('Commande introuvable');
        }

        $isProprio = ($restaurant->getProprietaire() === $user);
        $isServeurDuResto = $restaurant->getServeurs()->contains($user);
        if (!$isProprio && !$isServeurDuResto) {
            throw $this->createAccessDeniedException('Accès interdit');
        }

        $form = $this->createForm(CommandeType::class, $commande, ['restaurant' => $restaurant]);
        $form->handleRequest($request);

        $produitsGroupes = ['Entrées' => [], 'Plats' => [], 'Desserts' => [], 'Boissons' => [], 'Menus' => []];
        $choices = $form->get('produits')->getConfig()->getAttribute('choice_list')->getChoices();

        foreach ($choices as $index => $produit) {
            $categorie = null;
            if ($produit instanceof Boisson) {
                $categorie = 'Boissons';
            } elseif ($produit instanceof Menu) {
                $categorie = 'Menus';
            } elseif ($produit instanceof Plat) {
                $typePlat = $produit->getTypePlat()->getLib();
                if ('Entrée' === $typePlat) {
                    $categorie = 'Entrées';
                } elseif ('Plat' === $typePlat) {
                    $categorie = 'Plats';
                } elseif ('Dessert' === $typePlat) {
                    $categorie = 'Desserts';
                }
            }
            if ($categorie) {
                $produitsGroupes[$categorie][$index] = $produit;
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $qtys = $request->request->all('qtys');
            $total = 0;

            foreach ($commande->getProduits() as $p) {
                $commande->removeProduit($p);
            }

            $produitsSelectionnes = $form->get('produits')->getData();

            foreach ($produitsSelectionnes as $produit) {
                $qte = isset($qtys[$produit->getId()]) ? (int) $qtys[$produit->getId()] : 1;
                $total += ($produit->getPrixProduit() * $qte);

                for ($i = 0; $i < $qte; ++$i) {
                    $commande->addProduit($produit);
                }
            }

            $commande->setPrixCommande($total);
            $em->flush();

            return $this->redirectToRoute('app_commande_index', ['id' => $restaurant->getId()]);
        }

        return $this->render('commande/create.html.twig', [
            'form' => $form->createView(),
            'restaurant' => $restaurant,
            'table' => $commande->getTables(),
            'serveur' => $user,
            'produitsGroupes' => array_filter($produitsGroupes),
        ]);
    }

    #[Route('/commande/{id}/update-status', name: 'app_commande_update_etat', methods: ['POST'])]
    public function updateStatus(Request $request, Commande $commande, EntityManagerInterface $em): Response
    {
        $nouvelEtat = \App\Enum\EnumEtatCommande::tryFrom($request->request->get('nouvel_etat'));

        if ($nouvelEtat) {
            $commande->setEtatCommande($nouvelEtat);
            $em->flush();
            $this->addFlash('success', 'Etat de la commande mis à jour avec succès.');
        }

        return $this->redirectToRoute('app_commande_index', [
            'id' => $commande->getRestaurant()->getId(),
        ]);
    }
}
