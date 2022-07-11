<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends AbstractWebTestCase
{
    public function SetUp(): void
    {
        parent::SetUp();
        $this->admin = $this->getAdmin();
        $this->user = $this->createUser('user');
    }

    public function testAccessPageListUsersWhenUserNotConnectedOrNotAuthorize(): void
    {
        $this->client->request('GET', '/users');
        $this->assertResponseRedirects('/login'); // 302

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); // 403
    }

    public function testAccessPageListUsersWhenUserIsAuthorize(): void
    {
        $this->client->loginUser($this->admin);
        $this->client->followRedirects();
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }

    public function testAccessPageCreateUsersWhenUserNotConnectedOrNotAuthorize(): void
    {
        $this->client->request('GET', '/users/create');
        $this->assertResponseRedirects('/login'); // 302

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); // 403
    }

    public function testAccessPageCreateUsersWhenUserIsAuthorize(): void
    {
        // Check if page is accessible by admin
        $this->client->followRedirects();
        $this->client->loginUser($this->admin);
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200

        // Check if user exists in database and remove it if it exists
        $this->removeUser('user_test');

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user_test',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user@test.fr',
            ]);
        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('div.alert.alert-success:contains("L\'utilisateur a bien été ajouté.")');
    }

    public function testAccessPageEditUsersWhenUserNotConnectedOrNotAuthorize(): void
    {
        $url = '/users/'.$this->user->getId().'/edit';
        $this->client->request('GET', $url);
        $this->assertResponseRedirects('/login'); // 302

        $this->client->loginUser($this->user);
        $this->client->request('GET', $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); // 403
    }

    public function testAccessPageEditUsersWhenUserIsAuthorize(): void
    {
        $url = '/users/'.$this->user->getId().'/edit';
        $this->client->followRedirects();
        $this->client->loginUser($this->admin);
        $crawler = $this->client->request('GET', $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200

        // Check if user exists in database and remove it if it exists
        $this->removeUser('user_test_modify');
        // User form
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'user_test_modify',
            'user[password][first]' => 'passwordModify',
            'user[password][second]' => 'passwordModify',
            'user[email]' => 'usermodify@test.fr',
            ]);
        $crawler = $this->client->submit($form);

        $this->assertSelectorExists('div.alert.alert-success:contains("L\'utilisateur a bien été modifié")');
    }
}
