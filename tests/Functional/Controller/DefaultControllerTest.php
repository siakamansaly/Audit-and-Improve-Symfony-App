<?php

namespace App\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\AbstractWebTestCase;

class DefaultControllerTest extends AbstractWebTestCase
{
    public function testAccessPageIndexWhenUserNotConnected()
    {
        $this->client->request('GET', '/');
        $this->client->followRedirects();
        $this->assertResponseRedirects('/login');
    }

    public function testAccessPageIndexWhenUserConnected()
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->client->loginUser($user);

        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }
}
