<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'locationID', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $ClientID = null;

    #[ORM\OneToOne(inversedBy: 'locationID', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicule $VehiculeID = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeFin = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $Statut = [];

    #[ORM\Column]
    private ?int $Prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientID(): ?Client
    {
        return $this->ClientID;
    }

    public function setClientID(Client $ClientID): self
    {
        $this->ClientID = $ClientID;

        return $this;
    }

    public function getVehiculeID(): ?Vehicule
    {
        return $this->VehiculeID;
    }

    public function setVehiculeID(Vehicule $VehiculeID): self
    {
        $this->VehiculeID = $VehiculeID;

        return $this;
    }

    public function getDateDeDebut(): ?\DateTimeInterface
    {
        return $this->DateDeDebut;
    }

    public function setDateDeDebut(\DateTimeInterface $DateDeDebut): self
    {
        $this->DateDeDebut = $DateDeDebut;

        return $this;
    }

    public function getDateDeFin(): ?\DateTimeInterface
    {
        return $this->DateDeFin;
    }

    public function setDateDeFin(\DateTimeInterface $DateDeFin): self
    {
        $this->DateDeFin = $DateDeFin;

        return $this;
    }

    public function getStatut(): array
    {
        return $this->Statut;
    }

    public function setStatut(array $Statut): self
    {
        $this->Statut = $Statut;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function __toString(){
        return string($this->id) ;
    }

}
