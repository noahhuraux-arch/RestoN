<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idMenu = null;

    #[ORM\Column(length: 64)]
    private ?string $libMenu = null;

    #[ORM\Column]
    private ?float $prixMenu = null;

    #[ORM\Column]
    private ?bool $visible = null;

    public function getidMenu(): ?int
    {
        return $this->idMenu;
    }

    public function getLibMenu(): ?string
    {
        return $this->libMenu;
    }

    public function setLibMenu(string $libMenu): static
    {
        $this->libMenu = $libMenu;

        return $this;
    }

    public function getPrixMenu(): ?float
    {
        return $this->prixMenu;
    }

    public function setPrixMenu(float $prixMenu): static
    {
        $this->prixMenu = $prixMenu;

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
}
