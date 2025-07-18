<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

#[UniqueEntity('email')]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $contact = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Administrateur::class, orphanRemoval: true)]
    private Collection $administrateurs;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Client::class, orphanRemoval: true)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Entreprise::class, orphanRemoval: true)]
    private Collection $entreprises;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Artisan::class, orphanRemoval: true)]
    private Collection $artisans;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $statusCompte = 0;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Intervention::class)]
    private Collection $interventions;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: DisponibiliteArtisan::class)]
    private Collection $disponibiliteArtisans;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    #[ORM\OneToMany(mappedBy: 'idUtilisateur', targetEntity: Evenement::class)]
    private Collection $evenements;

    public function __construct(){
        $this->createdAt = new \DateTimeImmutable();
        $this->administrateurs = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->artisans = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->disponibiliteArtisans = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

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

    /**
     * @return Collection<int, Administrateur>
     */
    public function getAdministrateurs(): Collection
    {
        return $this->administrateurs;
    }

    public function addAdministrateur(Administrateur $administrateur): self
    {
        if (!$this->administrateurs->contains($administrateur)) {
            $this->administrateurs->add($administrateur);
            $administrateur->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeAdministrateur(Administrateur $administrateur): self
    {
        if ($this->administrateurs->removeElement($administrateur)) {
            // set the owning side to null (unless already changed)
            if ($administrateur->getIdUtilisateur() === $this) {
                $administrateur->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getIdUtilisateur() === $this) {
                $client->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entreprise>
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises->add($entreprise);
            $entreprise->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->removeElement($entreprise)) {
            // set the owning side to null (unless already changed)
            if ($entreprise->getIdUtilisateur() === $this) {
                $entreprise->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Artisan>
     */
    public function getArtisans(): Collection
    {
        return $this->artisans;
    }

    public function addArtisan(Artisan $artisan): self
    {
        if (!$this->artisans->contains($artisan)) {
            $this->artisans->add($artisan);
            $artisan->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeArtisan(Artisan $artisan): self
    {
        if ($this->artisans->removeElement($artisan)) {
            // set the owning side to null (unless already changed)
            if ($artisan->getIdUtilisateur() === $this) {
                $artisan->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    public function getStatusCompte(): ?int
    {
        return $this->statusCompte;
    }

    public function setStatusCompte(int $statusCompte): self
    {
        $this->statusCompte = $statusCompte;

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getIdUtilisateur() === $this) {
                $intervention->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DisponibiliteArtisan>
     */
    public function getDisponibiliteArtisans(): Collection
    {
        return $this->disponibiliteArtisans;
    }

    public function addDisponibiliteArtisan(DisponibiliteArtisan $disponibiliteArtisan): self
    {
        if (!$this->disponibiliteArtisans->contains($disponibiliteArtisan)) {
            $this->disponibiliteArtisans->add($disponibiliteArtisan);
            $disponibiliteArtisan->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeDisponibiliteArtisan(DisponibiliteArtisan $disponibiliteArtisan): self
    {
        if ($this->disponibiliteArtisans->removeElement($disponibiliteArtisan)) {
            // set the owning side to null (unless already changed)
            if ($disponibiliteArtisan->getIdUtilisateur() === $this) {
                $disponibiliteArtisan->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): self
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getIdUtilisateur() === $this) {
                $rendezVouse->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getIdUtilisateur() === $this) {
                $evenement->setIdUtilisateur(null);
            }
        }

        return $this;
    }
}
