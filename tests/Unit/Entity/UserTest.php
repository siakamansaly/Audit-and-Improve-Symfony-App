<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();
        $user->setEmail('true@test.com');
        $user->setUsername('username');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);

        $this->assertTrue('true@test.com' === $user->getEmail());
        $this->assertTrue('username' === $user->getUsername());
        $this->assertTrue('password' === $user->getPassword());
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user->setEmail('true@test.com');
        $user->setUsername('username');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);

        $this->assertFalse('false@test.com' === $user->getEmail());
        $this->assertFalse('falseusername' === $user->getUsername());
        $this->assertFalse('falsepassword' === $user->getPassword());
        $this->assertFalse($user->getRoles() === ['ROLE_ADMIN']);
    }

    public function testIsEmpty(): void
    {
        $user = new User();
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getUsername());
        $this->assertEmpty($user->getPassword());
        $this->assertEmpty($user->getId());
        $this->assertEmpty($user->getUserIdentifier());
        $this->assertEmpty($user->getTasks());
        $this->assertEmpty($user->isAnonymous());
    }

    public function testAddGetRemoveTasks(): void
    {
        $user = new User();
        $task = new Task();

        $this->assertEmpty($user->getTasks());

        $user->addTask($task);
        $this->assertContains($task, $user->getTasks());

        $user->removeTask($task);
        $this->assertEmpty($user->getTasks());
    }
}
