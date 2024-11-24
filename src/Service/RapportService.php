<?php

namespace App\Service;

use App\Entity\Rapport;
use App\Entity\Alimentation;
use Doctrine\ORM\EntityManagerInterface;

class RapportService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createRapportWithAlimentation(Rapport $rapport, $form): void
    {
        $alimentationSelectionnee = $form->get('nomNourriture')->getData();

        if ($alimentationSelectionnee) {
            $alimentation = new Alimentation();
            $alimentation->setNomNourriture($alimentationSelectionnee->getNomNourriture());
            $alimentation->setQuantiteNourriture($form->get('quantiteNourriture')->getData());
            $alimentation->setCommentaireVeterinaire($form->get('commentaireVeterinaire')->getData());

            $rapport->setAlimentation($alimentation);
            $this->entityManager->persist($alimentation);
        }

        $this->entityManager->persist($rapport);
        $this->entityManager->flush();
    }
    
    public function updateRapportWithAlimentation(Rapport $rapport, $form): void
    {
        $alimentationSelectionnee = $form->get('nomNourriture')->getData();
        $quantiteNourriture = $form->get('quantiteNourriture')->getData();
        $commentaireVeterinaire = $form->get('commentaireVeterinaire')->getData();

        $alimentation = $rapport->getAlimentation() ?: new Alimentation();
        $alimentation->setNomNourriture($alimentationSelectionnee->getNomNourriture());
        $alimentation->setQuantiteNourriture($quantiteNourriture);
        $alimentation->setCommentaireVeterinaire($commentaireVeterinaire);

        if (!$rapport->getAlimentation()) {
            $rapport->setAlimentation($alimentation);
            $this->entityManager->persist($alimentation);
        }

        $this->entityManager->persist($rapport);
        $this->entityManager->flush();
    }
}
