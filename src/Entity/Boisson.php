<?php

namespace App\Entity;

use App\Repository\BoissonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoissonRepository::class)]
class Boisson extends Produit
{
    #[ORM\Column]
    private ?bool $alcoolise = null;

    #[ORM\ManyToOne(inversedBy: 'boissons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $idRestau = null;

    public function isAlcoolise(): ?bool
    {
        return $this->alcoolise;
    }

    public function setAlcoolise(bool $alcoolise): static
    {
        $this->alcoolise = $alcoolise;

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
