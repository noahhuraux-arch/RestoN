<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
#[ApiResource]
class Plat extends Produit
{
    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: true)]
    private ?TypePlat $typePlat = null;

    /** @var Collection<int, Menu> */
    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'idPlat')]
    private Collection $menus;

    #[ORM\Column]
    private ?bool $vegetarien = null;

    public function __construct()
    {
        parent::__construct();
        $this->menus = new ArrayCollection();
    }

    public function getTypePlat(): ?TypePlat
    {
        return $this->typePlat;
    }

    public function setTypePlat(?TypePlat $typePlat): static
    {
        $this->typePlat = $typePlat;

        return $this;
    }

    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->addIdPlat($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeIdPlat($this);
        }

        return $this;
    }

    public function isVegetarien(): ?bool
    {
        return $this->vegetarien;
    }

    public function setVegetarien(bool $vegetarien): static
    {
        $this->vegetarien = $vegetarien;

        return $this;
    }
}
