<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * AppFixtures class.
 *
 * Add all test data to the database.
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\DataFixtures
 */
class AppFixtures extends Fixture
{
    private UserService $userService;

    /**
     * AppFixtures constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Load datas in database.
     *
     * Create a user anonymous and tasks for the user anonymous.
     *
     * Create users and tasks for each user.
     *
     * Create admin user.
     *
     * Create tasks without linked user.
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create anonymous user in database.
        $anonymousUser = $this->userService->createUser();

        // Create tasks for anonymous user.
        for ($i = 0; $i < rand(1, 7); ++$i) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text());
            $task->setUser($anonymousUser);
            $manager->persist($task);
            $manager->flush();
        }

        // Create users in database.
        for ($i = 0; $i < rand(2, 5); ++$i) {
            $user = $this->userService->createUser('user'.$i);

            // Create tasks for user.
            for ($j = 0; $j < rand(1, 7); ++$j) {
                $task = new Task();
                $task->setTitle($faker->word());
                $task->setContent($faker->text());
                $task->setUser($user);
                $manager->persist($task);
                $manager->flush();
            }
        }

        // Create admin in database.
        $admin = $this->userService->createUser('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

        // Create tasks without user.
        for ($i = 0; $i < rand(1, 7); ++$i) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text());
            $task->setUser(null);
            $manager->persist($task);
            $manager->flush();
        }
    }
}
