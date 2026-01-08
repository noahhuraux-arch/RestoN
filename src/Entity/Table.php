<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbPlace = null;

    #[ORM\Column(nullable: true)]
    private ?bool $disponible = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'table')]
    private Collection $reservations;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'tables')]
    private ?Restaurant $restaurant = null;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'table')]
    private Collection $commande;

    #[ORM\Column]
    private ?int $numero = null;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): static
    {
        $this->nbPlace = $nbPlace;
        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(?bool $disponible): static
    {
        $this->disponible = $disponible;
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

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commande->contains($commande)) {
            $this->commande->add($commande);
            $commande->setTables($this);
        }
        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commande->removeElement($commande)) {
            if ($commande->getTables() === $this) {
                $commande->setTables(null);
            }
        }
        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }
}
