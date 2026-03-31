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

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Restaurant $restaurant = null;

    #[ORM\Column]
    private ?bool $isPaye = false;

    /**
     * @var Collection<int, CommandeQuantite>
     */
    #[ORM\OneToMany(targetEntity: CommandeQuantite::class, mappedBy: 'commande', cascade: ['persist'], orphanRemoval: true)]
    private Collection $commandeQuantites;

    #[ORM\Column(enumType: EnumEtatCommande::class)]
    private ?EnumEtatCommande $etatCommande = EnumEtatCommande::WaitingTreatment;

    public function __construct()
    {
        $this->dateCommande = new \DateTime();
        $this->commandeQuantites = new ArrayCollection();
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
        return $this->commandeQuantites->map(
            fn ($cq) => $cq->getProduit()
        );
    }

    public function addProduit(Produit $produit, int $quantite = 1): static
    {
        $existingCq = $this->commandeQuantites->findFirst(
            fn ($key, CommandeQuantite $cq) => $cq->getProduit() === $produit
        );

        if ($existingCq) {
            $existingCq->setQuantite($quantite);
        } else {
            $cq = new CommandeQuantite();
            $cq->setProduit($produit);
            $cq->setCommande($this);
            $cq->setQuantite($quantite);
            $this->commandeQuantites->add($cq);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        $cq = $this->commandeQuantites->findFirst(
            fn ($key, CommandeQuantite $cq) => $cq->getProduit() === $produit
        );

        if (null !== $cq) {
            $this->commandeQuantites->removeElement($cq);
        }

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

    /**
     * @return Collection<int, CommandeQuantite>
     */
    public function getCommandeQuantites(): Collection
    {
        return $this->commandeQuantites;
    }

    public function addCommandeQuantite(CommandeQuantite $commandeQuantite): static
    {
        if (!$this->commandeQuantites->contains($commandeQuantite)) {
            $this->commandeQuantites->add($commandeQuantite);
            $commandeQuantite->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeQuantite(CommandeQuantite $commandeQuantite): static
    {
        if ($this->commandeQuantites->removeElement($commandeQuantite)) {
            if ($commandeQuantite->getCommande() === $this) {
                $commandeQuantite->setCommande(null);
            }
        }

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
