<?php

namespace App\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\AbstractWebTestCase;

class SecurityControllerTest extends AbstractWebTestCase
{
    public function testAccessPageLoginWithBadCredentials(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'anonymous',
            '_password' => 'badpassword', ]);
        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('div.alert.alert-danger:contains("Invalid credentials")');
    }

    public function testAccessPageLoginSuccessfullWithoutRedirect(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');
        $user = $this->getUser();
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $user->getUsername(),
            '_password' => $user->getPassword(), ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }

    public function testAccessPageLoginSuccessfullWithRedirect(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/');
        $user = $this->getUser();
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $user->getUsername(),
            '_password' => $user->getPassword(), ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }
}
