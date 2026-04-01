<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'boisson' => Boisson::class,
    'menu' => Menu::class,
    'plat' => Plat::class,
])]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
    ],
    normalizationContext: ['groups' => ['Produit_Read']],
    denormalizationContext: ['groups' => ['Produit_Write']]
)]
abstract class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Groups(['Produit_Read', 'Produit_Write'])]
    private ?string $libProduit = null;

    #[ORM\Column]
    #[Groups(['Produit_Read', 'Produit_Write'])]
    private ?float $prixProduit = null;

    #[ORM\Column]
    #[Groups(['Produit_Read', 'Produit_Write'])]
    private ?bool $visible = null;

    #[ORM\Column(length: 1024, nullable: true)]
    #[Groups(['Produit_Read', 'Produit_Write'])]
    private ?string $descriptionProduit = null;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Produit_Read', 'Produit_Write'])]
    #[ApiProperty(example: '/api/restaurants/1')]
    private ?Restaurant $restaurant = null;

    /**
     * @var Collection<int, Allergene>
     */
    #[ORM\ManyToMany(targetEntity: Allergene::class, inversedBy: 'produits')]
    #[Groups(['Produit_Read', 'Produit_Write'])]
    #[ApiProperty(example: '[/api/allergenes/1, /api/allergenes/2]')]
    private Collection $allergenes;

    public function __construct()
    {
        $this->allergenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibProduit(): ?string
    {
        return $this->libProduit;
    }

    public function setLibProduit(string $libProduit): static
    {
        $this->libProduit = $libProduit;

        return $this;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(float $prixProduit): static
    {
        $this->prixProduit = $prixProduit;

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

    public function getDescriptionProduit(): ?string
    {
        return $this->descriptionProduit;
    }

    public function setDescriptionProduit(?string $descriptionProduit): static
    {
        $this->descriptionProduit = $descriptionProduit;

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

    /**
     * @return Collection<int, Allergene>
     */
    public function getAllergenes(): Collection
    {
        return $this->allergenes;
    }

    public function addAllergene(Allergene $allergene): static
    {
        if (!$this->allergenes->contains($allergene)) {
            $this->allergenes->add($allergene);
        }

        return $this;
    }

    public function removeAllergene(Allergene $allergene): static
    {
        $this->allergenes->removeElement($allergene);

        return $this;
    }
}
