<?php

namespace App\Command;

use App\Entity\Task;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Command to link tasks to a user in the database.
 *
 * All tasks without a user are linked to user anonymous in the database.
 *
 * Command : php bin/console tasks:linker
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Command
 */
class LinkEmptyTasksCommand extends Command
{
    protected static $defaultName = 'tasks:linker';
    protected static $defaultDescription = 'Used to link empty tasks to an anonymous user.';

    private EntityManagerInterface $entityManager;
    private UserService $userService;

    /**
     * LinkEmptyTasksCommand constructor.
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        parent::__construct();
    }

    /**
     * Configure the command.
     */
    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    /**
     * Execute the command.
     *
     * Get all tasks without a user and link them to an anonymous user in the database.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $execute = new SymfonyStyle($input, $output);

        $tasks = $this->entityManager->getRepository(Task::class)->findBy(['user' => null]);

        $execute->title('Tasks linker');
        $execute->info(['Found '.count($tasks).' tasks without user.']);
        if (0 === count($tasks)) {
            return 0;
        }
        $execute->section('Details');

        foreach ($tasks as $task) {
            $task->setUser($this->userService->userByDefault());
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $output->writeln('Task #'.$task->getId().' linked to anonymous user.');
        }
        $execute->newLine();
        $execute->success('Tasks linked to anonymous user.');

        return Command::SUCCESS;
    }
}
