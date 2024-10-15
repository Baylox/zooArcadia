<?php

namespace App\Entity;

use App\Repository\AlimentationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlimentationRepository::class)]
class Alimentation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomNourriture = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $quantiteNourriture = null;  

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaireVeterinaire = null;

    #[ORM\ManyToOne(inversedBy: 'alimentations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rapport $rapport = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomNourriture(): ?string
    {
        return $this->nomNourriture;
    }

    public function setNomNourriture(string $nomNourriture): static
    {
        $this->nomNourriture = $nomNourriture;

        return $this;
    }

    public function getQuantiteNourriture(): ?float
    {
        return $this->quantiteNourriture;
    }

    public function setQuantiteNourriture(float $quantiteNourriture): static  
    {
        $this->quantiteNourriture = $quantiteNourriture;

        return $this;
    }

    public function getCommentaireVeterinaire(): ?string
    {
        return $this->commentaireVeterinaire;
    }

    public function setCommentaireVeterinaire(?string $commentaireVeterinaire): static
    {
        $this->commentaireVeterinaire = $commentaireVeterinaire;

        return $this;
    }

    public function getRapport(): ?Rapport
    {
        return $this->rapport;
    }

    public function setRapport(?Rapport $rapport): static
    {
        $this->rapport = $rapport;

        return $this;
    }
}
