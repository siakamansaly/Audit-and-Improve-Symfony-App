<?php

namespace App\Tests\Functional\Service;

use App\Entity\User;
use App\Service\UserService;
use App\Tests\Functional\AbstractWebTestCase;

class UserServiceTest extends AbstractWebTestCase
{
    private UserService $userService;

    public function SetUp(): void
    {
        parent::SetUp();
        if($this->client->getContainer()->get(UserService::class) instanceof UserService) {
            $this->userService = $this->client->getContainer()->get(UserService::class);
        } 
        $this->removeUser('anonymousTest');
    }

    public function testUserByDefault(): void
    {
        $anonymousUser = $this->userService->userByDefault('anonymousTest');
        $this->assertInstanceOf(User::class, $anonymousUser);
        $this->assertEquals('anonymousTest', $anonymousUser->getUsername());
    }
}
