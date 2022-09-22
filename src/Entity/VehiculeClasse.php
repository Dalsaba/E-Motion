<?php

namespace App\Entity;

use App\Repository\VehiculeClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeClasseRepository::class)]
class VehiculeClasse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $Prix = null;
/*
    #[ORM\OneToMany(mappedBy: 'Classe', targetEntity: Vehicule::class)]
    private Collection $vehicules;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $priceID = null;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
    }
*/
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection<int, Vehicule>
     */
/*
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Vehicule $vehicule): self
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->setClasse($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): self
    {
        if ($this->vehicules->removeElement($vehicule)) {
            // set the owning side to null (unless already changed)
            if ($vehicule->getClasse() === $this) {
                $vehicule->setClasse(null);
            }
        }

        return $this;
    }
*/

    public function getPriceID(): ?string
    {
        return $this->priceID;
    }

    public function setPriceID(?string $priceID): self
    {
        $this->priceID = $priceID;

        return $this;
    }

    public function __toString(){
        return (string) $this->id;
    }
}
