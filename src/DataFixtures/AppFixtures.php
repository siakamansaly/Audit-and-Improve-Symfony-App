<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
use App\Service\UserService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create anonymous user in database.
        $anonymousUser = $this->userService->createUser();

        // Create tasks for anonymous user.
        for ($i = 0; $i < rand(1, 7); $i++) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text());
            $task->setUser($anonymousUser);
            $manager->persist($task);
            $manager->flush();
        }

        // Create users in database.
        for ($i = 0; $i < rand(1, 4); $i++) {
            $user = $this->userService->createUser('user' . $i);

            // Create tasks for user.
            for ($j = 0; $j < rand(1, 7); $j++) {
                $task = new Task();
                $task->setTitle($faker->word());
                $task->setContent($faker->text());
                $task->setUser($user);
                $manager->persist($task);
                $manager->flush();
            }

        }

        // Create tasks without user.
        for ($i = 0; $i < rand(1, 7); $i++) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text());
            $task->setUser(null);
            $manager->persist($task);
            $manager->flush();
        }

    }


}
