<?php

namespace App\Entity;

use App\Enum\EnumEtatCommande;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $prixCommande = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Serveur $serveur = null;

    #[ORM\ManyToOne]
    private ?Table $tables = null;

    /**
     * Relation ManyToMany vers l'entité Produit (classe mère)
     * On utilise une Collection pour stocker la liste des articles sélectionnés.
     */
    #[ORM\ManyToMany(targetEntity: Produit::class)]
    #[ORM\JoinTable(name: 'commande_produit')]
    private Collection $produits;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Restaurant $restaurant = null;

    #[ORM\Column]
    private ?bool $isPaye = false;

    #[ORM\Column(enumType: EnumEtatCommande::class)]
    private ?EnumEtatCommande $etatCommande = EnumEtatCommande::WaitingTreatment;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->dateCommande = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixCommande(): ?float
    {
        return $this->prixCommande;
    }

    public function setPrixCommande(float $prixCommande): static
    {
        $this->prixCommande = $prixCommande;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getServeur(): ?Serveur
    {
        return $this->serveur;
    }

    public function setServeur(?Serveur $serveur): static
    {
        $this->serveur = $serveur;

        return $this;
    }

    public function getTables(): ?Table
    {
        return $this->tables;
    }

    public function setTables(?Table $tables): static
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->isPaye;
    }

    public function setIsPaye(bool $isPaye): static
    {
        $this->isPaye = $isPaye;

        return $this;
    }

    public function getEtatCommande(): ?EnumEtatCommande
    {
        return $this->etatCommande;
    }

    public function setEtatCommande(EnumEtatCommande $etatCommande): static
    {
        $this->etatCommande = $etatCommande;

        return $this;
    }
}
