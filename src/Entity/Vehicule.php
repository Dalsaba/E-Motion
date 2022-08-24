<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $immatricule = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $num_serie = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column]
    private ?int $nb_kilometre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_achat = null;

    #[ORM\Column]
    private ?int $prix_achat = null;

    #[ORM\OneToOne(mappedBy: 'VehiculeID', cascade: ['persist', 'remove'])]
    private ?Location $locationID = null;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    private ?VehiculeClasse $Classe = null;


    public function __construct()
    {
        $this->contenuPaniers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImmatricule(): ?string
    {
        return $this->immatricule;
    }

    public function setImmatricule(string $immatricule): self
    {
        $this->immatricule = $immatricule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getNumSerie(): ?string
    {
        return $this->num_serie;
    }

    public function setNumSerie(string $num_serie): self
    {
        $this->num_serie = $num_serie;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getNbKilometre(): ?int
    {
        return $this->nb_kilometre;
    }

    public function setNbKilometre(int $nb_kilometre): self
    {
        $this->nb_kilometre = $nb_kilometre;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(\DateTimeInterface $date_achat): self
    {
        $this->date_achat = $date_achat;

        return $this;
    }

    public function getPrixAchat(): ?int
    {
        return $this->prix_achat;
    }

    public function setPrixAchat(int $prix_achat): self
    {
        $this->prix_achat = $prix_achat;

        return $this;
    }

    public function getLocationID(): ?Location
    {
        return $this->locationID;
    }

    public function setLocationID(Location $locationID): self
    {
        // set the owning side of the relation if necessary
        if ($locationID->getVehiculeID() !== $this) {
            $locationID->setVehiculeID($this);
        }

        $this->locationID = $locationID;

        return $this;
    }

    public function getClasse(): ?VehiculeClasse
    {
        return $this->Classe;
    }

    public function setClasse(?VehiculeClasse $Classe): self
    {
        $this->Classe = $Classe;

        return $this;
    }
}
