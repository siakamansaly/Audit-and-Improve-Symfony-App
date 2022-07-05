<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
    }

    /**
     * Set a user by Default.
     *
     * @return User $User
     */
    public function userByDefault(): User
    {
        $anonymousUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'anonymous']);

        if (null === $anonymousUser) {
            $anonymousUser = $this->createAnonymousUser();
        }

        return $anonymousUser;
    }

    /**
     * Create anonymous user in database.
     *
     * @return User $User
     */
    public function createAnonymousUser(): User
    {
        $anonymousUser = new User();
        $anonymousUser->setUsername('anonymous');
        $anonymousUser->setEmail('anonymous@anonymous.fr');
        $password = $this->hasher->hashPassword($anonymousUser, 'password');
        $anonymousUser->setPassword($password);
        $anonymousUser->setRoles(['ROLE_USER']);
        $this->em->persist($anonymousUser);
        $this->em->flush();

        return $anonymousUser;
    }
}
