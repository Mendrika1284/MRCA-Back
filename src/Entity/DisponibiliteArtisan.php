<?php

namespace App\Entity;

use App\Repository\DisponibiliteArtisanRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DisponibiliteArtisanRepository::class)]
class DisponibiliteArtisan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'disponibiliteArtisans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUtilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'disponibiliteArtisans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DevisClient $idDevisClient = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $jour = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $heureDebut = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $heureFin = [];

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

    public function getIdDevisClient(): ?DevisClient
    {
        return $this->idDevisClient;
    }

    public function setIdDevisClient(?DevisClient $idDevisClient): self
    {
        $this->idDevisClient = $idDevisClient;

        return $this;
    }

    public function getJour(): array
    {
        return $this->jour;
    }

    public function setJour(array $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHeureDebut(): array
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(array $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): array
    {
        return $this->heureFin;
    }

    public function setHeureFin(array $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }
}
