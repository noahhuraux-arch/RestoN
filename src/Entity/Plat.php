<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPlat = null;

    #[ORM\Column(length: 64)]
    private ?string $libPlat = null;

    #[ORM\Column]
    private ?float $prixPlat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $visible = null;

    #[ORM\Column(length: 1024)]
    private ?string $descriptionPlat = null;

    public function getidPlat(): ?int
    {
        return $this->idPlat;
    }

    public function getLibPlat(): ?string
    {
        return $this->libPlat;
    }

    public function setLibPlat(string $libPlat): static
    {
        $this->libPlat = $libPlat;

        return $this;
    }

    public function getPrixPlat(): ?float
    {
        return $this->prixPlat;
    }

    public function setPrixPlat(float $prixPlat): static
    {
        $this->prixPlat = $prixPlat;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getDescriptionPlat(): ?string
    {
        return $this->descriptionPlat;
    }

    public function setDescriptionPlat(string $descriptionPlat): static
    {
        $this->descriptionPlat = $descriptionPlat;

        return $this;
    }
}
