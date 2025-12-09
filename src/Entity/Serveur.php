<?php

namespace App\Entity;

use App\Repository\ServeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServeurRepository::class)]
class Serveur extends Personne
{
    #[ORM\Column]
    private ?float $salaire = null;

    #[ORM\ManyToOne(inversedBy: 'serveurs')]
    private ?Restaurant $restaurant = null;

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): static
    {
        $this->salaire = $salaire;

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
}
