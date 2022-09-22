<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[UniqueEntity('immatricule', message: "Cette immatricule esite déjà, veuillez modifier votre saisie")]
#[UniqueEntity('num_serie', message: "Ce numéro de série exite déjà, veuillez modifier votre saisie")]

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
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

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    private ?VehiculeClasse $Classe = null;

    #[ORM\ManyToOne(inversedBy: 'id_vehicule')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $id_client = null;

    public function getId(): ?Uuid
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

    public function getNum_Serie(): ?string
    {
        return $this->num_serie;
    }

    public function setNum_Serie(string $num_serie): self
    {
        $this->num_serie = $num_serie;

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

    public function getNb_Kilometre(): ?int
    {
        return $this->nb_kilometre;
    }

    public function setNb_Kilometre(int $nb_kilometre): self
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

    public function getDate_Achat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDate_Achat(\DateTimeInterface $date_achat): self
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

    public function getPrix_Achat(): ?int
    {
        return $this->prix_achat;
    }

    public function setPrix_Achat(int $prix_achat): self
    {
        $this->prix_achat = $prix_achat;

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

    public function getIdClient(): ?Client
    {
        return $this->id_client;
    }
    
    public function setIdClient(?Client $id_client): self
    {
        $this->id_client = $id_client;
    
        return $this;
    }

    public function getId_Client(): ?Client
    {
        return $this->id_client;
    }
    
    public function setId_Client(?Client $id_client): self
    {
        $this->id_client = $id_client;
    
        return $this;
    }

    public function __toString(){
        return $this->id_client;
    }
}