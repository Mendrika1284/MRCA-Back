<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArtisanRepository;


#[ApiResource]
#[ORM\Entity(repositoryClass: ArtisanRepository::class)]
class Artisan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'artisans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUtilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $civilite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoProfile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoCouverture = null;

    #[ORM\Column(length: 255)]
    private ?string $statusJuridique = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $tva = null;

    #[ORM\Column(length: 255)]
    private ?string $kbis = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column(length: 255)]
    private ?string $bic = null;

    #[ORM\ManyToOne(inversedBy: 'artisans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieMetier $idCategorieMetier = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 18)]
    private ?string $positionX = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 18)]
    private ?string $positionY = null;

    #[ORM\OneToMany(mappedBy: 'idArtisan', targetEntity: DevisClient::class)]
    private Collection $devisClients;

    #[ORM\Column]
    private ?int $estOccupe = null;

    #[ORM\OneToMany(mappedBy: 'idArtisan', targetEntity: Intervention::class)]
    private Collection $interventions;

    #[ORM\OneToMany(mappedBy: 'idArtisan', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->devisClients = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getPhotoProfile(): ?string
    {
        return $this->photoProfile;
    }

    public function setPhotoProfile(string $photoProfile): self
    {
        $this->photoProfile = $photoProfile;

        return $this;
    }

    public function getPhotoCouverture(): ?string
    {
        return $this->photoCouverture;
    }

    public function setPhotoCouverture(?string $photoCouverture): self
    {
        $this->photoCouverture = $photoCouverture;

        return $this;
    }

    public function getStatusJuridique(): ?string
    {
        return $this->statusJuridique;
    }

    public function setStatusJuridique(string $statusJuridique): self
    {
        $this->statusJuridique = $statusJuridique;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTva(): ?string
    {
        return $this->tva;
    }

    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getKbis(): ?string
    {
        return $this->kbis;
    }

    public function setKbis(string $kbis): self
    {
        $this->kbis = $kbis;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(string $bic): self
    {
        $this->bic = $bic;

        return $this;
    }

    public function getIdCategorieMetier(): ?CategorieMetier
    {
        return $this->idCategorieMetier;
    }

    public function setIdCategorieMetier(?CategorieMetier $idCategorieMetier): self
    {
        $this->idCategorieMetier = $idCategorieMetier;

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

    /**
     * @return Collection<int, DevisClient>
     */
    public function getDevisClients(): Collection
    {
        return $this->devisClients;
    }

    public function addDevisClient(DevisClient $devisClient): self
    {
        if (!$this->devisClients->contains($devisClient)) {
            $this->devisClients->add($devisClient);
            $devisClient->setIdArtisan($this);
        }

        return $this;
    }

    public function removeDevisClient(DevisClient $devisClient): self
    {
        if ($this->devisClients->removeElement($devisClient)) {
            // set the owning side to null (unless already changed)
            if ($devisClient->getIdArtisan() === $this) {
                $devisClient->setIdArtisan(null);
            }
        }

        return $this;
    }

    public function getEstOccupe(): ?int
    {
        return $this->estOccupe;
    }

    public function setEstOccupe(int $estOccupe): self
    {
        $this->estOccupe = $estOccupe;

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
            $intervention->setIdArtisan($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getIdArtisan() === $this) {
                $intervention->setIdArtisan(null);
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
            $rendezVouse->setIdArtisan($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getIdArtisan() === $this) {
                $rendezVouse->setIdArtisan(null);
            }
        }

        return $this;
    }
}
