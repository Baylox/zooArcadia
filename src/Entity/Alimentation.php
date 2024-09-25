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
    private ?string $quantiteNourriture = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaireVeterinaire = null;

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

    public function getQuantiteNourriture(): ?string
    {
        return $this->quantiteNourriture;
    }

    public function setQuantiteNourriture(string $quantiteNourriture): static
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
}
