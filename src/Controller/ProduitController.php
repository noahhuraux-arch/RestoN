<?php

namespace App\Controller;

use App\Repository\BoissonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function listBoisson(BoissonRepository $service): Response
    {
        $boisson = $service->findBy([], ['alcoolise' => 'ASC', 'prixProduit' => 'ASC']);

        return $this->render('produit/index.html.twig', ['boissons' => $boisson]);
    }
}
