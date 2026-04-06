<?php

namespace App\Entity;

use App\Repository\CommandeQuantiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CommandeQuantiteRepository::class)]
#[UniqueEntity(
    fields: ['produit', 'commande'],
    message: 'This command as already a quantity for this product.', )
]
class CommandeQuantite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite;

    #[ORM\ManyToOne(inversedBy: 'commandeQuantites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit;

    #[ORM\ManyToOne(inversedBy: 'commandeQuantites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
