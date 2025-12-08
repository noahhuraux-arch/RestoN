<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends Personne
{
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'client')]
    private Collection $reservations;

    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function setReservations(Collection $reservations): void
    {
        $this->reservations = $reservations;
    }
}
