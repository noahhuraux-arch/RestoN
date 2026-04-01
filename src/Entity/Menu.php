<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
    ]
)]
class Menu extends Produit
{
    /**
     * @var Collection<int, Plat>
     */
    #[ORM\ManyToMany(targetEntity: Plat::class, inversedBy: 'menus')]
    #[ApiProperty(example: '[/api/plats/1, /api/plats/2]')]
    private Collection $idPlat;

    public function __construct()
    {
        parent::__construct();
        $this->idPlat = new ArrayCollection();
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getIdPlat(): Collection
    {
        return $this->idPlat;
    }

    public function addIdPlat(Plat $idPlat): static
    {
        if (!$this->idPlat->contains($idPlat)) {
            $this->idPlat->add($idPlat);
        }

        return $this;
    }

    public function removeIdPlat(Plat $idPlat): static
    {
        $this->idPlat->removeElement($idPlat);

        return $this;
    }
}
