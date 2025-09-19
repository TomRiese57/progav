<?php

namespace App\Tests\Controller;

use App\Entity\Burger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BurgerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $burgerRepository;
    private string $path = '/burger/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->burgerRepository = $this->manager->getRepository(Burger::class);

        foreach ($this->burgerRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Burger index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'burger[name]' => 'Testing',
            'burger[price]' => 'Testing',
            'burger[pain]' => 'Testing',
            'burger[oignon]' => 'Testing',
            'burger[sauce]' => 'Testing',
            'burger[image]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->burgerRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Burger();
        $fixture->setName('My Title');
        $fixture->setPrice('My Title');
        $fixture->setPain('My Title');
        $fixture->setOignon('My Title');
        $fixture->setSauce('My Title');
        $fixture->setImage('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Burger');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Burger();
        $fixture->setName('Value');
        $fixture->setPrice('Value');
        $fixture->setPain('Value');
        $fixture->setOignon('Value');
        $fixture->setSauce('Value');
        $fixture->setImage('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'burger[name]' => 'Something New',
            'burger[price]' => 'Something New',
            'burger[pain]' => 'Something New',
            'burger[oignon]' => 'Something New',
            'burger[sauce]' => 'Something New',
            'burger[image]' => 'Something New',
        ]);

        self::assertResponseRedirects('/burger/');

        $fixture = $this->burgerRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getPain());
        self::assertSame('Something New', $fixture[0]->getOignon());
        self::assertSame('Something New', $fixture[0]->getSauce());
        self::assertSame('Something New', $fixture[0]->getImage());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Burger();
        $fixture->setName('Value');
        $fixture->setPrice('Value');
        $fixture->setPain('Value');
        $fixture->setOignon('Value');
        $fixture->setSauce('Value');
        $fixture->setImage('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/burger/');
        self::assertSame(0, $this->burgerRepository->count([]));
    }
}
