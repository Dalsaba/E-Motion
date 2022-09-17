<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDeFin = null;

    #[ORM\Column]
    private ?int $Prix = null;

    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'locations')]
    private Collection $ClientID;

    #[ORM\ManyToMany(targetEntity: Vehicule::class, inversedBy: 'locations')]
    private Collection $VehiculeID;

    #[ORM\Column(length: 255)]
    private ?string $Statut = null;

    public function __construct()
    {
        $this->ClientID = new ArrayCollection();
        $this->VehiculeID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClientID(): Collection
    {
        return $this->ClientID;
    }

    public function addClientID(Client $clientID): self
    {
        if (!$this->ClientID->contains($clientID)) {
            $this->ClientID->add($clientID);
        }

        return $this;
    }

    public function removeClientID(Client $clientID): self
    {
        $this->ClientID->removeElement($clientID);

        return $this;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getVehiculeID(): Collection
    {
        return $this->VehiculeID;
    }

    public function addVehiculeID(Vehicule $vehiculeID): self
    {
        if (!$this->VehiculeID->contains($vehiculeID)) {
            $this->VehiculeID->add($vehiculeID);
        }

        return $this;
    }

    public function removeVehiculeID(Vehicule $vehiculeID): self
    {
        $this->VehiculeID->removeElement($vehiculeID);

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->Statut;
    }

    public function setStatut(string $Statut): self
    {
        $this->Statut = $Statut;

        return $this;
    }

    public function __toString(){
        return string($this->id) ;
    }



}
