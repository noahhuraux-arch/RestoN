<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ApiResource(
    operations: [
        new Get(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
    ],
    normalizationContext: ['groups' => ['Restaurant_Read']],
    denormalizationContext: ['groups' => ['Restaurant_Write']]
)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64, maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.')]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: 'Ichiraku Ramen')]
    private ?string $libRestau = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100, maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères.")]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: 'Rue du Hokage')]
    private ?string $adrRestau = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[0-9]{5}$/', message: "Le code postal n'est pas valide.")]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: '31500')]
    private ?int $cpRestau = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50, maxMessage: 'La ville ne doit pas dépasser {{ limit }} caractères.')]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: 'Konoha')]
    private ?string $villeRestau = null;

    #[ORM\Column]
    #[Groups(['Restaurant_Read'])]
    #[ApiProperty(example: 12)]
    private ?int $nbTable = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: 2)]
    private ?int $nbEtoiles = null;

    #[ORM\ManyToOne(inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    #[Ignore]
    private ?Proprietaire $proprietaire = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4})$/', message: 'Format de téléphone invalide')]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: '0579153085')]
    private ?string $tel_restau = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[ApiProperty(example: 'ichirakuRamen@gmail.com')]
    private ?string $email_restau = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banniere = null;

    #[ORM\OneToMany(targetEntity: Horaire::class, mappedBy: 'restaurant', cascade: ['remove'], orphanRemoval: true)]
    #[Ignore]
    private Collection $horaires;

    #[ORM\OneToMany(targetEntity: Table::class, mappedBy: 'restaurant', cascade: ['remove'], orphanRemoval: true)]
    #[Ignore]
    private Collection $tables;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'restaurant', cascade: ['remove'])]
    #[Ignore]
    private Collection $reservations;

    #[ORM\OneToMany(targetEntity: Serveur::class, mappedBy: 'restaurant', cascade: ['remove'])]
    #[Ignore]
    private Collection $serveurs;

    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'restaurant', cascade: ['remove'])]
    #[ApiProperty(example: '[/api/produits/1, /api/produits/2]')]
    #[Groups(['Restaurant_Read', 'Restaurant_Write'])]
    #[Ignore]
    private Collection $produits;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'restaurant')]
    #[ApiProperty(example: '[/api/commandes/1, /api/commandes/2]')]
    #[Ignore]
    private Collection $commandes;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
        $this->tables = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->serveurs = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getTelRestau(): ?string
    {
        return $this->tel_restau;
    }

    public function setTelRestau(string $tel_restau): static
    {
        $this->tel_restau = $tel_restau;

        return $this;
    }

    public function getEmailRestau(): ?string
    {
        return $this->email_restau;
    }

    public function setEmailRestau(string $email_restau): static
    {
        $this->email_restau = $email_restau;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBanniere(): ?string
    {
        return $this->banniere;
    }

    public function setBanniere(?string $banniere): static
    {
        $this->banniere = $banniere;

        return $this;
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): static
    {
        if (!$this->tables->contains($table)) {
            $this->tables->add($table);
            $table->setRestaurant($this);
        }

        return $this;
    }

    public function removeTable(Table $table): static
    {
        if ($this->tables->removeElement($table)) {
            if ($table->getRestaurant() === $this) {
                $table->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setRestaurant($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getRestaurant() === $this) {
                $produit->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function getServeurs(): Collection
    {
        return $this->serveurs;
    }

    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setRestaurant($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getRestaurant() === $this) {
                $commande->setRestaurant(null);
            }
        }

        return $this;
    }
}
