<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idHoraire = null;

    #[ORM\Column(length: 64)]
    private ?string $lundi = null;

    #[ORM\Column(length: 64)]
    private ?string $mardi = null;

    #[ORM\Column(length: 64)]
    private ?string $mercredi = null;

    #[ORM\Column(length: 64)]
    private ?string $jeudi = null;

    #[ORM\Column(length: 64)]
    private ?string $vendredi = null;

    #[ORM\Column(length: 64)]
    private ?string $samedi = null;

    #[ORM\Column(length: 64)]
    private ?string $dimanche = null;

    public function getidHoraire(): ?int
    {
        return $this->idHoraire;
    }

    public function getLundi(): ?string
    {
        return $this->lundi;
    }

    public function setLundi(string $lundi): static
    {
        $this->lundi = $lundi;

        return $this;
    }

    public function getMardi(): ?string
    {
        return $this->mardi;
    }

    public function setMardi(string $mardi): static
    {
        $this->mardi = $mardi;

        return $this;
    }

    public function getMercredi(): ?string
    {
        return $this->mercredi;
    }

    public function setMercredi(string $mercredi): static
    {
        $this->mercredi = $mercredi;

        return $this;
    }

    public function getJeudi(): ?string
    {
        return $this->jeudi;
    }

    public function setJeudi(string $jeudi): static
    {
        $this->jeudi = $jeudi;

        return $this;
    }

    public function getVendredi(): ?string
    {
        return $this->vendredi;
    }

    public function setVendredi(string $vendredi): static
    {
        $this->vendredi = $vendredi;

        return $this;
    }

    public function getSamedi(): ?string
    {
        return $this->samedi;
    }

    public function setSamedi(string $samedi): static
    {
        $this->samedi = $samedi;

        return $this;
    }

    public function getDimanche(): ?string
    {
        return $this->dimanche;
    }

    public function setDimanche(string $dimanche): static
    {
        $this->dimanche = $dimanche;

        return $this;
    }
}
