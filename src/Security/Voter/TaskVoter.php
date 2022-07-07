<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Voter class for tasks.
 *
 * Check if the user is allowed to edit or delete a task.
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Security\Voter
 */
class TaskVoter extends Voter
{
    public const DELETE = 'TASK_DELETE';
    private $security;

    /**
     * The constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // dd($attribute, $subject);
        return in_array($attribute, [self::DELETE]) && $subject instanceof \App\Entity\Task;
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $isAnonymous = (null === $subject->getUser()) ? true : ($subject->getUser()->isAnonymous());

        // if the user is admin, they can do anything
        if ($this->security->isGranted('ROLE_ADMIN', $user) && $isAnonymous) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($subject, $user);
                break;
        }

        return false;
    }

    /**
     * Check if the user is allowed to delete a task.
     *
     * @param Task $task the task to delete
     * @param User $user the user who wants to delete the task
     *
     * @return bool true if the user is allowed to delete the task, false otherwise
     */
    public function canDelete(Task $task, User $user): bool
    {
        return $task->getUser() === $user;
    }
}
