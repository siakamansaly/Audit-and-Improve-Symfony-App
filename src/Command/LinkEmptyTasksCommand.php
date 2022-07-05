<?php

namespace App\Command;

use App\Entity\Task;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkEmptyTasksCommand extends Command
{
    protected static $defaultName = 'tasks:linker';
    protected static $defaultDescription = 'Used to link empty tasks to an anonymous user.';
    private EntityManagerInterface $em;
    private UserService $userService;

    public function __construct(EntityManagerInterface $em, UserService $userService)
    {
        $this->em = $em;
        $this->userService = $userService;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // Select all tasks without user
        $tasks = $this->em->getRepository(Task::class)->findBy(['user' => null]);

        $io->title('Tasks linker');
        $io->info(['Found ' . count($tasks) . ' tasks without user.']);
        if(count($tasks) === 0)
        {
            return 0;
        }
        $io->section('Details');
        // For each task, link it to an anonymous user
        foreach ($tasks as $task) {
            $task->setUser($this->userService->userByDefault());
            $this->em->persist($task);
            $this->em->flush();
            $output->writeln('Task #' . $task->getId() . ' linked to anonymous user.');
        }
        $io->newLine();
        $io->success('Tasks linked to anonymous user.');

        return Command::SUCCESS;
    }
}
