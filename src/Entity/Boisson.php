<?php

namespace App\Entity;

use App\Repository\BoissonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoissonRepository::class)]
class Boisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idBoisson = null;

    #[ORM\Column(length: 64)]
    private ?string $libBoisson = null;

    #[ORM\Column]
    private ?float $prixBoisson = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column]
    private ?bool $alcoolise = null;

    #[ORM\Column(length: 1024)]
    private ?string $descriptionBoisson = null;

    public function getidBoisson(): ?int
    {
        return $this->idBoisson;
    }

    public function getLibBoisson(): ?string
    {
        return $this->libBoisson;
    }

    public function setLibBoisson(string $libBoisson): static
    {
        $this->libBoisson = $libBoisson;

        return $this;
    }

    public function getPrixBoisson(): ?float
    {
        return $this->prixBoisson;
    }

    public function setPrixBoisson(float $prixBoisson): static
    {
        $this->prixBoisson = $prixBoisson;

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

    public function isAlcoolise(): ?bool
    {
        return $this->alcoolise;
    }

    public function setAlcoolise(bool $alcoolise): static
    {
        $this->alcoolise = $alcoolise;

        return $this;
    }

    public function getDescriptionBoisson(): ?string
    {
        return $this->descriptionBoisson;
    }

    public function setDescriptionBoisson(string $descriptionBoisson): static
    {
        $this->descriptionBoisson = $descriptionBoisson;

        return $this;
    }
}
