<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $libRestau = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $adrRestau = null;

    #[ORM\Column(nullable: true)]
    private ?int $cpRestau = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $villeRestau = null;

    #[ORM\Column]
    private ?int $nbTable = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbEtoiles = null;

    #[ORM\ManyToOne(inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proprietaire $proprietaire = null;

    public function getid(): ?int
    {
        return $this->id;
    }

    public function getLibRestau(): ?string
    {
        return $this->libRestau;
    }

    public function setLibRestau(string $libRestau): static
    {
        $this->libRestau = $libRestau;

        return $this;
    }

    public function getAdrRestau(): ?string
    {
        return $this->adrRestau;
    }

    public function setAdrRestau(?string $adrRestau): static
    {
        $this->adrRestau = $adrRestau;

        return $this;
    }

    public function getCpRestau(): ?int
    {
        return $this->cpRestau;
    }

    public function setCpRestau(?int $cpRestau): static
    {
        $this->cpRestau = $cpRestau;

        return $this;
    }

    public function getVilleRestau(): ?string
    {
        return $this->villeRestau;
    }

    public function setVilleRestau(?string $villeRestau): static
    {
        $this->villeRestau = $villeRestau;

        return $this;
    }

    public function getNbTable(): ?int
    {
        return $this->nbTable;
    }

    public function setNbTable(int $nbTable): static
    {
        $this->nbTable = $nbTable;

        return $this;
    }

    public function getNbEtoiles(): ?int
    {
        return $this->nbEtoiles;
    }

    public function setNbEtoiles(?int $nbEtoiles): static
    {
        $this->nbEtoiles = $nbEtoiles;

        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }
}
