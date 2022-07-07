<?php

namespace App\Tests\Functional;

use App\Entity\Task;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function SetUp(): void
    {
        $this->client = static::createClient();
        $this->manager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * Get the user with the given username.
     *
     * @return User
     */
    public function getUser(?string $username = null): ?User
    {
        if (null === $username) {
            return $this->manager->getRepository(User::class)->findOneBy([]);
        }

        return $this->manager->getRepository(User::class)->findOneByUsername($username);
    }

    /**
     * Get the user with role Administrator.
     *
     * @return User
     */
    public function getAdmin(): ?User
    {
        $admin = $this->manager->getRepository(User::class)->findOneByUsername('admin');
        if (null === $admin) {
            $admin = $this->client->getContainer()->get(UserService::class)->createUser('admin');
            $admin->setRoles(['ROLE_ADMIN']);
            $this->manager->persist($admin);
            $this->manager->flush();
        }

        return $admin;
    }

    public function createUser(?string $username = 'user'): User
    {
        $user = $this->manager->getRepository(User::class)->findOneByUsername($username);
        if (null === $user) {
            $user = $this->client->getContainer()->get(UserService::class)->createUser($username);
        }

        return $user;
    }

    public function createTask(?string $title = 'Task', ?string $content = 'Task', ?User $user = null): Task
    {
        $task = $this->manager->getRepository(Task::class)->findOneByTitle($title);
        if (null === $task) {
            $task = new Task();
            $task->setTitle($title);
            $task->setContent($content);
            $task->setUser($user);
            $this->manager->persist($task);
            $this->manager->flush();
        }

        return $task;
    }

    public function removeUser(?string $username = 'user'): void
    {
        $user = $this->manager->getRepository(User::class)->findOneByUsername($username);
        if (null !== $user) {
            $tasks = $user->getTasks();
            foreach ($tasks as $task) {
                $this->manager->remove($task);
            }
            $this->manager->remove($user);
            $this->manager->flush();
        }
    }
}
