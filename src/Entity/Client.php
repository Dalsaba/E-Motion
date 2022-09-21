<?php

namespace App\Entity;

use App\Repository\ClientRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

use Misd\PhoneNumberBundle\Validator\Constraints as MisdAssert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity('email', message: "Cette addresse email, veuillez modifier votre saisie")]
#[UniqueEntity('numeroPermis', message: "Ce numéro de permis exite déjà, veuillez modifier votre saisie")]
#[UniqueEntity('telephone', message: "Ce numéro de téléphone exite déjà, veuillez modifier votre saisie")]

class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column(type : 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $telephone = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank]
    private ?string $numeroPermis = null;

    #[ORM\OneToMany(mappedBy: 'id_client', targetEntity: Vehicule::class, orphanRemoval: true)]
    private Collection $id_vehicule;

    public function __construct()
    {
        $this->id_vehicule = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNumeroPermis(): ?string
    {
        return $this->numeroPermis;
    }

    public function setNumeroPermis(string $numeroPermis): self
    {
        $this->numeroPermis = $numeroPermis;

        return $this;
    }

    public function __toString(){
        return $this->roles.' '.$this-> email.': ['.$this->nom.' '.$this->prenom.']';
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getIdVehicule(): Collection
    {
        return $this->id_vehicule;
    }

    public function addIdVehicule(Vehicule $idVehicule): self
    {
        if (!$this->id_vehicule->contains($idVehicule)) {
            $this->id_vehicule->add($idVehicule);
            $idVehicule->setIdClient($this);
        }

        return $this;
    }

    public function removeIdVehicule(Vehicule $idVehicule): self
    {
        if ($this->id_vehicule->removeElement($idVehicule)) {
            // set the owning side to null (unless already changed)
            if ($idVehicule->getIdClient() === $this) {
                $idVehicule->setIdClient(null);
            }
        }

        return $this;
    }
}
