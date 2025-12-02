<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat extends Produit
{
    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: true)]
    private ?TypePlat $typePlat = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $idRestau = null;

    public function getTypePlat(): ?TypePlat
    {
        return $this->typePlat;
    }

    public function setTypePlat(?TypePlat $typePlat): static
    {
        $this->typePlat = $typePlat;

        return $this;
    }

    public function getIdRestau(): ?Restaurant
    {
        return $this->idRestau;
    }

    public function setIdRestau(?Restaurant $idRestau): static
    {
        $this->idRestau = $idRestau;

        return $this;
    }
}
