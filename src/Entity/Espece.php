<?php

namespace App\Entity;

use App\Repository\EspeceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspeceRepository::class)]
class Espece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $typeEspece = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $evaluationExtinction = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $traitsCaracteristiques = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeEspece(): ?string
    {
        return $this->typeEspece;
    }

    public function setTypeEspece(string $typeEspece): static
    {
        $this->typeEspece = $typeEspece;

        return $this;
    }

    public function getEvaluationExtinction(): ?string
    {
        return $this->evaluationExtinction;
    }

    public function setEvaluationExtinction(?string $evaluationExtinction): static
    {
        $this->evaluationExtinction = $evaluationExtinction;

        return $this;
    }

    public function getTraitsCaracteristiques(): ?string
    {
        return $this->traitsCaracteristiques;
    }

    public function setTraitsCaracteristiques(?string $traitsCaracteristiques): static
    {
        $this->traitsCaracteristiques = $traitsCaracteristiques;

        return $this;
    }
}
