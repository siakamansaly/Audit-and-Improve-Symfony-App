<?php

namespace App\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\AbstractWebTestCase;

class DefaultControllerTest extends AbstractWebTestCase
{
    public function testAccessPageIndexWhenUserNotConnected(): void
    {
        $this->client->request('GET', '/');
        $this->client->followRedirects();
        $this->assertResponseRedirects('/login');
    }

    public function testAccessPageIndexWhenUserConnected(): void
    {
        $user = $this->getUser();
        if (!$user) {
            $this->fail('User not found');
        }

        $this->client->loginUser($user);

        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }
}
