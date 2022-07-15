<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller to manage tasks.
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Controller
 */
class TaskController extends AbstractController
{
    private ManagerRegistry $doctrine;

    private UserService $userService;

    /**
     * The constructor.
     */
    public function __construct(ManagerRegistry $doctrine, UserService $userService)
    {
        $this->doctrine = $doctrine;
        $this->userService = $userService;
    }

    /**
     * Task list page.
     *
     * @Route("/tasks", name="task_list")
     */
    public function listAction(): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->doctrine->getRepository('App\Entity\Task')->findAll()]);
    }

    /**
     * Task creation page.
     *
     * @Route("/tasks/create", name="task_create")
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user instanceof User) {
                $user = $this->userService->userByDefault();
            }
            $task->setUser($user);

            $this->doctrine->getManager()->persist($task);
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Task edition page.
     *
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->doctrine->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * Task is done or not.
     *
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task): RedirectResponse
    {
        $task->toggle(!$task->isDone());
        $this->doctrine->getManager()->flush();

        switch ($task->isDone()) {
            case true:
                $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
                break;
            case false:
                $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));
                break;
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * Task deletion page.
     *
     * @Route("/tasks/{id}/delete", name="task_delete")
     *
     * @throws AccessDeniedException "TASK_DELETE" Voter is not granted.
     */
    public function deleteTaskAction(Task $task): RedirectResponse
    {
        $this->denyAccessUnlessGranted('TASK_DELETE', $task, 'Vous n\'avez pas le droit de supprimer cette tâche.');
        $this->doctrine->getManager()->remove($task);
        $this->doctrine->getManager()->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
