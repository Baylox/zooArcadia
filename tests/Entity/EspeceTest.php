<?php

namespace App\Tests\Entity;

use App\Entity\Espece;
use App\Entity\Animal;
use PHPUnit\Framework\TestCase;

class EspeceTest extends TestCase
{
    public function testGetId()
    {
        $espece = new Espece();
        $this->assertNull($espece->getId());
    }

    // Teste les méthodes getTypeEspece et setTypeEspece
    public function testGetSetTypeEspece()
    {
        $espece = new Espece();
        
        // Définit le type d'espèce
        $typeEspece = 'Mammifère';
        $espece->setTypeEspece($typeEspece);
        
        // Vérifie que le type d'espèce a été correctement défini
        $this->assertEquals($typeEspece, $espece->getTypeEspece());
    }

    // Teste les méthodes getEvaluationExtinction et setEvaluationExtinction
    public function testGetSetEvaluationExtinction()
    {
        $espece = new Espece();
        
        // Définit l'évaluation d'extinction
        $evaluationExtinction = 'En danger';
        $espece->setEvaluationExtinction($evaluationExtinction);
        
        // Vérifie que l'évaluation d'extinction a été correctement définie
        $this->assertEquals($evaluationExtinction, $espece->getEvaluationExtinction());
    }

    // Teste les méthodes getTraitsCaracteristiques et setTraitsCaracteristiques
    public function testGetSetTraitsCaracteristiques()
    {
        $espece = new Espece();
        
        // Définit les traits caractéristiques
        $traitsCaracteristiques = 'Grandes oreilles';
        $espece->setTraitsCaracteristiques($traitsCaracteristiques);
        
        // Vérifie que les traits caractéristiques ont été correctement définis
        $this->assertEquals($traitsCaracteristiques, $espece->getTraitsCaracteristiques());
    }

    // Teste les méthodes addAnimal et removeAnimal
    public function testAddRemoveAnimal()
    {
        $espece = new Espece();
        $animal = new Animal();

        // Vérifie que la liste des animaux est vide au départ
        $this->assertCount(0, $espece->getAnimals());

        // Ajoute un animal et vérifie s'il est ajouté correctement
        $espece->addAnimal($animal);
        $this->assertCount(1, $espece->getAnimals());
        $this->assertTrue($espece->getAnimals()->contains($animal));
        $this->assertSame($espece, $animal->getEspece());

        // Supprime l'animal et vérifie s'il est supprimé correctement
        $espece->removeAnimal($animal);
        $this->assertCount(0, $espece->getAnimals());
        $this->assertFalse($espece->getAnimals()->contains($animal));
        $this->assertNull($animal->getEspece());
    }
}