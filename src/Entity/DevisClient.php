<?php

namespace App\Entity;

use App\Entity\Artisan;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\DevisClientRepository;
use App\Controller\DevisClientApiController;

// Eto za zao
#[ApiResource]
#[ORM\Entity(repositoryClass: DevisClientRepository::class)]
class DevisClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devisClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeTravaux $idTypeTravaux = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 18)]
    private ?string $positionX = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 18)]
    private ?string $positionY = null;

    #[ORM\Column]
    private ?int $codePostal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(length: 255)]
    private ?string $document = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $infoSupplementaire = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $etat = null;

    #[ORM\ManyToOne(inversedBy: 'devisClients')]
    private ?Artisan $idArtisan = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(length: 255)]
    private ?string $choixTypeTravaux = null;

    #[ORM\OneToMany(mappedBy: 'idDevisClient', targetEntity: Intervention::class)]
    private Collection $interventions;

    #[ORM\OneToMany(mappedBy: 'idDevisClient', targetEntity: DisponibiliteArtisan::class)]
    private Collection $disponibiliteArtisans;

    #[ORM\OneToMany(mappedBy: 'idDevisClient', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    public function __construct(){
        $this->createdAt = new \DateTimeImmutable();
        $this->interventions = new ArrayCollection();
        $this->disponibiliteArtisans = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTypeTravaux(): ?TypeTravaux
    {
        return $this->idTypeTravaux;
    }

    public function setIdTypeTravaux(?TypeTravaux $idTypeTravaux): self
    {
        $this->idTypeTravaux = $idTypeTravaux;

        return $this;
    }

    public function getPositionX(): ?string
    {
        return $this->positionX;
    }

    public function setPositionX(string $positionX): self
    {
        $this->positionX = $positionX;

        return $this;
    }

    public function getPositionY(): ?string
    {
        return $this->positionY;
    }

    public function setPositionY(string $positionY): self
    {
        $this->positionY = $positionY;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getInfoSupplementaire(): ?string
    {
        return $this->infoSupplementaire;
    }

    public function setInfoSupplementaire(?string $infoSupplementaire): self
    {
        $this->infoSupplementaire = $infoSupplementaire;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIdArtisan(): ?Artisan
    {
        return $this->idArtisan;
    }

    public function setIdArtisan(?Artisan $idArtisan): self
    {
        $this->idArtisan = $idArtisan;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getChoixTypeTravaux(): ?string
    {
        return $this->choixTypeTravaux;
    }

    public function setChoixTypeTravaux(string $choixTypeTravaux): self
    {
        $this->choixTypeTravaux = $choixTypeTravaux;

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
            $intervention->setIdDevisClient($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getIdDevisClient() === $this) {
                $intervention->setIdDevisClient(null);
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
            $disponibiliteArtisan->setIdDevisClient($this);
        }

        return $this;
    }

    public function removeDisponibiliteArtisan(DisponibiliteArtisan $disponibiliteArtisan): self
    {
        if ($this->disponibiliteArtisans->removeElement($disponibiliteArtisan)) {
            // set the owning side to null (unless already changed)
            if ($disponibiliteArtisan->getIdDevisClient() === $this) {
                $disponibiliteArtisan->setIdDevisClient(null);
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
            $rendezVouse->setIdDevisClient($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getIdDevisClient() === $this) {
                $rendezVouse->setIdDevisClient(null);
            }
        }

        return $this;
    }
}
