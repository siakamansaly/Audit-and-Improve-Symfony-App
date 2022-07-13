<?php

namespace App\Tests\Integration\Command;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class LinkEmptyTasksCommandTest extends KernelTestCase
{
    public function testExecuteWithAction(): void
    {
        $kernel = self::bootKernel();

        $task = new Task();
        $task->setTitle('Test task');
        $task->setContent('Test description');
        $task->setUser(null);
        $task->setCreatedAt(new \DateTime());

        $manager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        if($manager instanceof \Doctrine\ORM\EntityManager) {
            $manager->persist($task);
            $manager->flush();
        }

        $application = new Application($kernel);

        $command = $application->find('tasks:linker');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] Tasks linked to anonymous user.', $output);
    }

    public function testExecuteWithoutAction(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('tasks:linker');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[INFO] Found 0 tasks without user.', $output);
    }
}
