<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

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
            '_password' => 'password', ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200

    }

    public function testAccessPageLoginWhenAlreadyConnected(): void
    {
        $user = $this->getUser();
        $this->client->loginUser($user);
        $this->client->request('GET', '/login');
        $this->client->followRedirects();
        $this->assertResponseRedirects('/');
    }

    public function testAccessPageLoginSuccessfullWithRedirect(): void
    {
        $user = $this->getUser();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/');
        
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $user->getUsername(),
            '_password' => 'password', ]);
        $crawler = $this->client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }

}
