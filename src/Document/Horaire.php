<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'horaires')]
class Horaire
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private string $jour;

    #[ODM\Field(type: 'string')]
    private string $heureOuverture;

    #[ODM\Field(type: 'string')]
    private string $heureFermeture;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getJour(): string
    {
        return $this->jour;
    }

    public function setJour(string $jour): self
    {
        $this->jour = $jour;
        return $this;
    }

    public function getHeureOuverture(): string
    {
        return $this->heureOuverture;
    }

    public function setHeureOuverture(string $heureOuverture): self
    {
        $this->heureOuverture = $heureOuverture;
        return $this;
    }

    public function getHeureFermeture(): string
    {
        return $this->heureFermeture;
    }

    public function setHeureFermeture(string $heureFermeture): self
    {
        $this->heureFermeture = $heureFermeture;
        return $this;
    }
}




