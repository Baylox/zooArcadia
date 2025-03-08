<?php

namespace App\Tests\Entity;

use App\Controller\AnimalController;
use App\Entity\Animal;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Rapport;

class AnimalTest extends TestCase
{
    
    public function testgetPrenom(): void
    {
        // Création d'un Animal
        $animal = new Animal(); 

        // On définit le prénom de l'animal
        $animal->setPrenom('Picsou');

        // On vérifie que le prénom de l'animal est bien 'Toto'
        $this->assertEquals('Picsou', $animal->getPrenom());
    }

    public function testAddRapport(): void
    {
        // Création d'un animal et d'un rapport
        $animal = new Animal();
        $rapport = new Rapport();

        // Ajout du rapport à l'animal
        $animal->addRapport($rapport);

        // Vérifie que l'animal contient bien le rapport
        $this->assertTrue($animal->getRapports()->contains($rapport));

        // Vérifie que le rapport a bien l'animal défini
        $this->assertSame($animal, $rapport->getAnimal());
    }
    // Test de la méthode removeRapport
    public function testRemoveRapport(): void
    {
        // Création d'un animal et d'un rapport
        $animal = new Animal();
        $rapport = new Rapport();

        // Ajout du rapport à l'animal
        $animal->addRapport($rapport);

        // Suppression du rapport de l'animal
        $animal->removeRapport($rapport);

        // Vérifie que l'animal ne contient plus le rapport
        $this->assertFalse($animal->getRapports()->contains($rapport));

        // Vérifie que le rapport n'a plus l'animal défini
        $this->assertNull($rapport->getAnimal());
    }

    public function testIncrementConsultations(): void
    {
        // Création d'un animal
        $animal = new Animal();

        // Récupération du nombre de consultations initial
        $initialConsultations = $animal->getConsultations();

        // Incrémentation
        $animal->incrementConsultations();

        // Vérifie que le nombre de consultations a bien augmenté de 1
        $this->assertEquals($initialConsultations + 1, $animal->getConsultations());
    }
}