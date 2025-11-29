<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $jour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $ouvertureMidi = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $fermetureMidi = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $ouvertureSoir = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $fermetureSoir = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ferme = null;

    #[ORM\ManyToOne(inversedBy: 'horaires')]
    private ?restaurant $restaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getOuvertureMidi(): ?\DateTime
    {
        return $this->ouvertureMidi;
    }

    public function setOuvertureMidi(?\DateTime $ouvertureMidi): static
    {
        $this->ouvertureMidi = $ouvertureMidi;

        return $this;
    }

    public function getFermetureMidi(): ?\DateTime
    {
        return $this->fermetureMidi;
    }

    public function setFermetureMidi(?\DateTime $fermetureMidi): static
    {
        $this->fermetureMidi = $fermetureMidi;

        return $this;
    }

    public function getOuvertureSoir(): ?\DateTime
    {
        return $this->ouvertureSoir;
    }

    public function setOuvertureSoir(?\DateTime $ouvertureSoir): static
    {
        $this->ouvertureSoir = $ouvertureSoir;

        return $this;
    }

    public function getFermetureSoir(): ?\DateTime
    {
        return $this->fermetureSoir;
    }

    public function setFermetureSoir(?\DateTime $fermetureSoir): static
    {
        $this->fermetureSoir = $fermetureSoir;

        return $this;
    }

    public function isFerme(): ?bool
    {
        return $this->ferme;
    }

    public function setFerme(?bool $ferme): static
    {
        $this->ferme = $ferme;

        return $this;
    }

    public function getRestaurant(): ?restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
