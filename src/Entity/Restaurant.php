<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64, maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.')]
    private ?string $libRestau = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100, maxMessage: "L'adresse ne doit pas dépasser {{ limit }} caractères.")]
    private ?string $adrRestau = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[0-9]{5}$/', message: "Le code postal n'est pas valide.")]
    private ?int $cpRestau = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50, maxMessage: 'La ville ne doit pas dépasser {{ limit }} caractères.')]
    private ?string $villeRestau = null;

    #[ORM\Column]
    private ?int $nbTable = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbEtoiles = null;

    #[ORM\ManyToOne(inversedBy: 'restaurants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Proprietaire $proprietaire = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4})$/', message: 'Format de téléphone invalide')]
    private ?string $tel_restau = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    private ?string $email_restau = null;

    /**
     * @var Collection<int, Horaire>
     */
    #[ORM\OneToMany(targetEntity: Horaire::class, mappedBy: 'restaurant')]
    private Collection $horaires;

    #[ORM\OneToMany(targetEntity: Table::class, mappedBy: 'restaurant')]
    private Collection $table;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'restaurant')]
    private Collection $reservations;

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function setReservations(Collection $reservations): void
    {
        $this->reservations = $reservations;
    }

    /**
     * @var Collection<int, Boisson>
     */
    #[ORM\OneToMany(targetEntity: Boisson::class, mappedBy: 'idRestau')]
    private Collection $boissons;

    /**
     * @var Collection<int, Plat>
     */
    #[ORM\OneToMany(targetEntity: Plat::class, mappedBy: 'idRestau')]
    private Collection $plats;

    /**
     * @var Collection<int, Serveur>
     */
    #[ORM\OneToMany(targetEntity: Serveur::class, mappedBy: 'restaurant')]
    private Collection $serveurs;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\OneToMany(targetEntity: Menu::class, mappedBy: 'idRestau')]
    private Collection $menus;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
        $this->boissons = new ArrayCollection();
        $this->plats = new ArrayCollection();
        $this->serveurs = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function getid(): ?int
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

    /**
     * @return Collection<int, Horaire>
     */
    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    public function addHoraire(Horaire $horaire): static
    {
        if (!$this->horaires->contains($horaire)) {
            $this->horaires->add($horaire);
            $horaire->setRestaurant($this);
        }

        return $this;
    }

    public function removeHoraire(Horaire $horaire): static
    {
        if ($this->horaires->removeElement($horaire)) {
            // set the owning side to null (unless already changed)
            if ($horaire->getRestaurant() === $this) {
                $horaire->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Boisson>
     */
    public function getBoissons(): Collection
    {
        return $this->boissons;
    }

    public function addBoisson(Boisson $boisson): static
    {
        if (!$this->boissons->contains($boisson)) {
            $this->boissons->add($boisson);
            $boisson->setIdRestau($this);
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): static
    {
        if ($this->boissons->removeElement($boisson)) {
            // set the owning side to null (unless already changed)
            if ($boisson->getIdRestau() === $this) {
                $boisson->setIdRestau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setIdRestau($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): static
    {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getIdRestau() === $this) {
                $plat->setIdRestau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Serveur>
     */
    public function getServeurs(): Collection
    {
        return $this->serveurs;
    }

    public function addServeur(Serveur $serveur): static
    {
        if (!$this->serveurs->contains($serveur)) {
            $this->serveurs->add($serveur);
            $serveur->setRestaurant($this);
        }

        return $this;
    }

    public function removeServeur(Serveur $serveur): static
    {
        if ($this->serveurs->removeElement($serveur)) {
            // set the owning side to null (unless already changed)
            if ($serveur->getRestaurant() === $this) {
                $serveur->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->setIdRestau($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getIdRestau() === $this) {
                $menu->setIdRestau(null);
            }
        }

        return $this;
    }
}
