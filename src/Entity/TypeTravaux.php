<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TypeTravauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: TypeTravauxRepository::class)]
class TypeTravaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'idTypeTravaux', targetEntity: DevisClient::class, orphanRemoval: true)]
    private Collection $devisClients;

    public function __construct()
    {
        $this->devisClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $devisClient->setIdTypeTravaux($this);
        }

        return $this;
    }

    public function removeDevisClient(DevisClient $devisClient): self
    {
        if ($this->devisClients->removeElement($devisClient)) {
            // set the owning side to null (unless already changed)
            if ($devisClient->getIdTypeTravaux() === $this) {
                $devisClient->setIdTypeTravaux(null);
            }
        }

        return $this;
    }
}
