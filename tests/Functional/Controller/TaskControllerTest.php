<?php

namespace App\Tests\Functional\Controller;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use App\Tests\Functional\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends AbstractWebTestCase
{
    private User $user;
    private User $userAnonymous;
    private User $userOther;
    private DateTime $date;
    private Task $taskAnonyme;
    private Task $taskOther;

    public function SetUp(): void
    {
        parent::SetUp();
        $this->user = $this->createUser('user');
        $this->userAnonymous = $this->createUser('anonymous');
        $this->userOther = $this->createUser('other');

        $this->taskAnonyme = $this->createTask('Task Anonyme', 'Task for anonyme user', $this->userAnonymous);
        $this->taskOther = $this->createTask('Task for other user', 'Task for other user', $this->userOther);

        $this->date = new \DateTime('now');
    }

    public function testAccessPageListTasksWhenUserConnected(): void
    {
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }

    public function testAccessPageListTasksWhenUserNotConnected(): void
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseRedirects('/login'); // 302
    }

    public function testAccessPageCreateTaskWhenUserConnected(): void
    {
        $this->client->followRedirects();
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK); // 200
    }

    public function testAccessPageCreateTaskWhenUserNotConnected(): void
    {
        $this->client->request('GET', '/tasks/create');
        $this->assertResponseRedirects('/login'); // 302
    }

    public function testCreateTask(): void
    {
        $this->client->followRedirects();
        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'test'.$this->date->format('Y-m-d'),
            'task[content]' => 'Ceci est un test', ]);
        $this->client->submit($form);
        $this->assertSelectorExists('div.alert.alert-success:contains("La tâche a été bien été ajoutée.")');
    }

    public function testEditTaskAction(): void
    {
        $this->client->followRedirects();
        $task = $this->manager->getRepository(Task::class)->findOneBy(['title' => 'test'.$this->date->format('Y-m-d')]);
        if(!$task) {
            $this->fail('Task not found');
        }
        $taskId = $task->getId();

        $this->client->loginUser($this->user);
        $crawler = $this->client->request('GET', '/tasks/'.$taskId.'/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'test modifié'.$this->date->format('Y-m-d'),
            'task[content]' => 'Ceci est un test modifié', ]);
        $this->client->submit($form);
        $this->assertSelectorExists('div.alert.alert-success:contains("La tâche a bien été modifiée.")');
    }

    public function testToggleTaskActionDoneAndNotDone(): void
    {
        $this->client->followRedirects();
        $task = $this->manager->getRepository(Task::class)->findOneBy(['title' => 'test modifié'.$this->date->format('Y-m-d')]);
        if(!$task) {
            $this->fail('Task not found');
        }
        $taskId = $task->getId();

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/'.$taskId.'/toggle');

        $this->assertSelectorExists('div.alert.alert-success');

        $this->client->request('GET', '/tasks/'.$taskId.'/toggle');

        $this->assertSelectorExists('div.alert.alert-success');
    }

    public function testDeleteOwnerTaskAction(): void
    {
        $this->client->followRedirects();
        $task = $this->manager->getRepository(Task::class)->findOneBy(['title' => 'test modifié'.$this->date->format('Y-m-d')]);
        if(!$task) {
            $this->fail('Task not found');
        }
        $taskId = $task->getId();

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/'.$taskId.'/delete');

        $this->assertSelectorExists('div.alert.alert-success:contains("La tâche a bien été supprimée.")');
    }

    public function testDeleteTaskOtherUserAction(): void
    {
        $this->client->followRedirects();

        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/'.$this->taskOther->getId().'/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); // 403
    }

    public function testDeleteTaskAnonymousByAdminAction(): void
    {
        $this->client->followRedirects();
        $admin = $this->getAdmin();
        $this->client->loginUser($admin);
        $this->client->request('GET', '/tasks/'.$this->taskAnonyme->getId().'/delete');

        $this->assertSelectorExists('div.alert.alert-success:contains("La tâche a bien été supprimée.")');
    }

    public function testDeleteTaskAnonymousByUserAction(): void
    {
        $this->client->followRedirects();
        $this->client->loginUser($this->user);
        $this->client->request('GET', '/tasks/'.$this->taskAnonyme->getId().'/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN); // 403
    }
}
