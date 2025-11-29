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
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $alcoolise = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
