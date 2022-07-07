<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testIsTrue(): void
    {
        $task = new Task();
        $date = new \DateTime();
        $user = new User();

        $task->setTitle('title');
        $task->setContent('content');
        $task->setCreatedAt($date);
        $task->setUser($user);

        $this->assertTrue('title' === $task->getTitle());
        $this->assertTrue('content' === $task->getContent());
        $this->assertTrue(false === $task->isDone());
        $this->assertTrue($task->getCreatedAt() === $date);
        $this->assertTrue($task->getUser() === $user);
    }

    public function testIsFalse(): void
    {
        $task = new Task();

        $date = new \DateTime();
        $user = new User();
        $userfalse = new User();
        $datefalse = new \DateTime();

        $task->setTitle('title');
        $task->setContent('content');
        $task->toggle($task->isDone());
        $task->setCreatedAt($date);
        $task->setUser($user);

        $this->assertFalse('falsetitle' === $task->getTitle());
        $this->assertFalse('falsecontent' === $task->getContent());
        $this->assertFalse(true === $task->isDone());
        $this->assertFalse($task->getCreatedAt() === $datefalse);
        $this->assertFalse($task->getUser() === $userfalse);
    }

    public function testIsEmpty(): void
    {
        $task = new Task();
        $this->assertEmpty($task->getTitle());
        $this->assertEmpty($task->getContent());
        $this->assertEmpty($task->isDone());
        $this->assertEmpty($task->getUser());
        $this->assertEmpty($task->getId());
    }
}
