<?php

namespace App\Tests\Controller;

use App\Entity\Animal;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class AnimalControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/animal/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Animal::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Animal index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'animal[prenom]' => 'Testing',
            'animal[etat]' => 'Testing',
            'animal[sexe]' => 'Testing',
            'animal[description]' => 'Testing',
            'animal[dob]' => 'Testing',
            'animal[espece]' => 'Testing',
            'animal[habitat]' => 'Testing',
            'animal[rapports]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Animal();
        $fixture->setPrenom('My Title');
        $fixture->setEtat('My Title');
        $fixture->setSexe('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDob('My Title');
        $fixture->setEspece('My Title');
        $fixture->setHabitat('My Title');
        $fixture->setRapports('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Animal');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Animal();
        $fixture->setPrenom('Value');
        $fixture->setEtat('Value');
        $fixture->setSexe('Value');
        $fixture->setDescription('Value');
        $fixture->setDob('Value');
        $fixture->setEspece('Value');
        $fixture->setHabitat('Value');
        $fixture->setRapports('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'animal[prenom]' => 'Something New',
            'animal[etat]' => 'Something New',
            'animal[sexe]' => 'Something New',
            'animal[description]' => 'Something New',
            'animal[dob]' => 'Something New',
            'animal[espece]' => 'Something New',
            'animal[habitat]' => 'Something New',
            'animal[rapports]' => 'Something New',
        ]);

        self::assertResponseRedirects('/animal/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getSexe());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDob());
        self::assertSame('Something New', $fixture[0]->getEspece());
        self::assertSame('Something New', $fixture[0]->getHabitat());
        self::assertSame('Something New', $fixture[0]->getRapports());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Animal();
        $fixture->setPrenom('Value');
        $fixture->setEtat('Value');
        $fixture->setSexe('Value');
        $fixture->setDescription('Value');
        $fixture->setDob('Value');
        $fixture->setEspece('Value');
        $fixture->setHabitat('Value');
        $fixture->setRapports('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/animal/');
        self::assertSame(0, $this->repository->count([]));
    }
}
