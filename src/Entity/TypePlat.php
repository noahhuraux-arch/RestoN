<?php

namespace App\Entity;

use App\Repository\TypePlatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePlatRepository::class)]
class TypePlat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idTypePlat = null;

    #[ORM\Column]
    private ?int $type = null;

    public function getidTypePlat(): ?int
    {
        return $this->idTypePlat;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }
}
