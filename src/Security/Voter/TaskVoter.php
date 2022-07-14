<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Task voter.
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
    private Security $security;

    /**
     * The constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     */
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::DELETE]) && $subject instanceof \App\Entity\Task;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * 
     * It is safe to assume that $attribute and $subject already passed the supports() method.
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if(!$subject instanceof Task) {
            return false;
        }
        $isAnonymous = (null === $subject->getUser()) ? true : ($subject->getUser()->isAnonymous());

        if ($this->security->isGranted('ROLE_ADMIN', $user) && $isAnonymous) {
            return true;
        }

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    /**
     * Check if the user is allowed to delete a task.
     *
     * @param Task $task the task to delete
     * @param UserInterface $user the user who wants to delete the task
     *
     * @return bool true if the user is allowed to delete the task, false otherwise
     */
    public function canDelete(Task $task, UserInterface $user): bool
    {
        return $task->getUser() === $user;
    }
}
