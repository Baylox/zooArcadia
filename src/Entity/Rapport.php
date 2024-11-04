<?php

namespace App\Entity;

use App\Repository\RapportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportRepository::class)]
class Rapport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRapport = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column(length: 50)]
    private ?string $nomNourriture = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $quantiteNourriture = null;

    #[ORM\Column(type: 'boolean')]
    private bool $changementAlimentation = false;

    #[ORM\ManyToOne(inversedBy: 'rapports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToOne(inversedBy: 'rapport', cascade: ['persist', 'remove'])]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDateRapport(): ?\DateTimeInterface
    {
        return $this->dateRapport;
    }

    public function setDateRapport(\DateTimeInterface $dateRapport): self
    {
        $this->dateRapport = $dateRapport;
        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;
        return $this;
    }

    public function getNomNourriture(): ?string
    {
        return $this->nomNourriture;
    }

    public function setNomNourriture(string $nomNourriture): self
    {
        $this->nomNourriture = $nomNourriture;
        return $this;
    }

    public function getQuantiteNourriture(): ?float
    {
        return $this->quantiteNourriture;
    }

    public function setQuantiteNourriture(float $quantiteNourriture): self
    {
        $this->quantiteNourriture = $quantiteNourriture;
        return $this;
    }

    public function getChangementAlimentation(): bool
    {
        return $this->changementAlimentation;
    }

    public function setChangementAlimentation(bool $changementAlimentation): self
    {
        $this->changementAlimentation = $changementAlimentation;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;
        return $this;
    }
}

