<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Service\UserService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Load test data into the database.
 * 
 * Command to load data: php bin/console doctrine:fixtures:load
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\DataFixtures
 */
class AppFixtures extends Fixture
{
    private UserService $userService;

    /**
     * The constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Load datas in database.
     *
     * 1- Create a user anonymous and tasks for the user anonymous.
     *
     * 2- Create users and tasks for each user.
     *
     * 3- Create admin user.
     *
     * 4- Create tasks without linked user.
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $anonymousUser = $this->userService->createUser();

        for ($i = 0; $i < rand(1, 7); ++$i) {
            $task = new Task();
            $task->setTitle($faker->word());
            $task->setContent($faker->text());
            $task->setUser($anonymousUser);
            $manager->persist($task);
            $manager->flush();
        }

        for ($i = 0; $i < rand(2, 5); ++$i) {
            
            $user = $this->userService->createUser('user'.$i);

            for ($j = 0; $j < rand(1, 7); ++$j) {
                $task = new Task();
                $task->setTitle($faker->word());
                $task->setContent($faker->text());
                $task->setUser($user);
                $manager->persist($task);
                $manager->flush();
            }
        }

        $admin = $this->userService->createUser('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

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
