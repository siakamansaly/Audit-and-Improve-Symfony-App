<?php

namespace App\Tests\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class AppFixturesTest extends AbstractWebTestCase
{

    protected AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->getContainer()->get(DatabaseToolCollection::class) instanceof DatabaseToolCollection) {
            $this->fail('DatabaseToolCollection not found');
        }
        $this->databaseTool = $this->getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testLoadDataFixtures(): void
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
