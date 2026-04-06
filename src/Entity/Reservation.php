<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['reservation:read']],
    denormalizationContext: ['groups' => ['reservation:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'client.nom' => 'partial',
])]
#[ApiFilter(DateFilter::class, properties: [
    'date'
])]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    #[ApiProperty(example: '2026-03-30T00:00:00+00:00')]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    #[ApiProperty(example: '1970-01-01T12:30:00+00:00')]
    private ?\DateTime $heure = null;

    #[ORM\Column]
    #[Groups(['reservation:read', 'reservation:write'])]
    #[ApiProperty(example: 3)]
    private ?int $nbPers = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'reservations', cascade: ['persist'])]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?Client $client = null;

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    #[ORM\ManyToOne(targetEntity: Table::class, inversedBy: 'reservations')]
    private ?Table $table = null;

    public function getTable(): ?Table
    {
        return $this->table;
    }

    public function setTable(?Table $table): void
    {
        $this->table = $table;
    }

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'reservations')]
    private ?Restaurant $restaurant = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'Reservee';

    #[ORM\Column]
    private ?int $numero = null;

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): void
    {
        $this->restaurant = $restaurant;
    }

    public function getid(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTime
    {
        return $this->heure;
    }

    public function setHeure(\DateTime $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getNbPers(): ?int
    {
        return $this->nbPers;
    }

    public function setNbPers(int $nbPers): static
    {
        $this->nbPers = $nbPers;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }
}
