<?php

namespace App\Tests\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Tests\Functional\AbstractWebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Component\HttpFoundation\Response;

class AppFixturesTest extends AbstractWebTestCase
{
    /**
     * @var AbstractDatabaseTool
     */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testLoadDataFixtures()
    {
        $this->databaseTool->loadFixtures([AppFixtures::class]);

        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');

        // Authentification sur Symfony pour le test avec le user récupéré en base
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'anonymous',
            '_password' => 'password', ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }
}
