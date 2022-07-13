<?php

namespace App\Tests\Functional;

use App\Entity\Task;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManager $manager;

    protected function SetUp(): void
    {
        $this->client = static::createClient();
        if(!$this->client->getContainer()->get('doctrine.orm.entity_manager') instanceof EntityManager) {
            $this->fail('Doctrine not found');
        }
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

        return $this->manager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    /**
     * Get the user with role Administrator.
     *
     * @return User
     */
    public function getAdmin(): User
    {
        $admin = $this->manager->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $userService = $this->client->getContainer()->get(UserService::class);
        if(!$userService instanceof UserService) {
            $this->fail('User service not found');
        }
        if (null === $admin) {

            $admin = $userService->createUser('admin');
            $admin->setRoles(['ROLE_ADMIN']);
            $this->manager->persist($admin);
            $this->manager->flush();
        }

        return $admin;
    }

    public function createUser(string $username = 'user'): User
    {
        $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $username]);
        $userService = $this->client->getContainer()->get(UserService::class);

        if(!$userService instanceof UserService) {
            $this->fail('User service not found');
        }
        if (null === $user) {
            $user = $userService->createUser($username);
        }

        return $user;
    }

    public function createTask(string $title = 'Task', string $content = 'Task', ?User $user = null): Task
    {
        $task = $this->manager->getRepository(Task::class)->findOneBy(['title' => $title]);
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
        $user = $this->manager->getRepository(User::class)->findOneBy(['username' => $username]);
        if (null !== $user) {
            $tasks = $user->getTasks();
            foreach ($tasks as $task) {
                $task->setUser(null);
                $this->manager->persist($task);
            }
            $this->manager->remove($user);
            $this->manager->flush();
        }
    }
}
