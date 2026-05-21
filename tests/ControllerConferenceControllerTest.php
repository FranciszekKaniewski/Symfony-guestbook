<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\WebBrowser;
use App\Entity\Conference;

class ControllerConferenceControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $shemaTools = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $shemaTools->dropSchema($metadata);
        $shemaTools->createSchema($metadata);

        $conference = new Conference();
        $conference->setCity('Kraków');
        $conference->setYear('2020');
        $conference->setIsInternational(true);
        $conference->setDescription("Testowa konferęcja");

        $this->entityManager->persist($conference);
        $this->entityManager->flush();
    }

    public function testHomepageLoads(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        // this->assertSelectorText('h1');
    }

    public function testCommentFormSubmit(): void
    {
        $conference = $this->entityManager->getRepository(Conference::class)->findOneBy(['city'=>'Kraków']);

        $this->assertNotNull($conference);

        $crawler = $this->client->request('GET', '/conference/'. $conference->getSlug());
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Wyślij komentarz', [
            'comment[author]' => 'Jan Kowalski',
            'comment[email]' => 'jan@example.com',
            'comment[text]' => 'TESTTESTTESTTEST',
        ]);

        $this->assertResponseRedirects();
    }

public function testNotFoundForInvalidSlug(): void
    {
        $this->client->request('GET', '/conference/nieistniejąca');
        
        $this->assertResponseStatusCodeSame(404);
    }
}
