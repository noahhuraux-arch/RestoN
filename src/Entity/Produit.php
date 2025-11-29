<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $libProduit = null;

    #[ORM\Column]
    private ?float $prixProduit = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $descriptionProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibProduit(): ?string
    {
        return $this->libProduit;
    }

    public function setLibProduit(string $libProduit): static
    {
        $this->libProduit = $libProduit;

        return $this;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(float $prixProduit): static
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->descriptionProduit;
    }

    public function setDescriptionProduit(?string $descriptionProduit): static
    {
        $this->descriptionProduit = $descriptionProduit;

        return $this;
    }
}
