<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Methods to manage users.
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Service
 */
class UserService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $hasher;

    /**
     * The constructor.
     *
     * @return void
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher)
    {
        $this->entityManager = $entityManager;
        $this->hasher = $hasher;
    }

    /**
     * Set a user by Default.
     * 
     * Without any parameter, the user named "anonymous" is set to a Default user.
     *
     * @return User $User
     */
    public function userByDefault(?string $username = 'anonymous'): User
    {
        $anonymousUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (null === $anonymousUser) {
            $anonymousUser = $this->createUser($username);
        }

        return $anonymousUser;
    }

    /**
     * Create an user in database.
     *
     * @return User $User
     */
    public function createUser(?string $username = 'anonymous', ?string $pass = 'password'): User
    {
        $anonymousUser = new User();
        $anonymousUser->setUsername($username);
        $anonymousUser->setEmail($username.'@anonymous.fr');
        $password = $this->hasher->hashPassword($anonymousUser, $pass);
        $anonymousUser->setPassword($password);
        $anonymousUser->setRoles(['ROLE_USER']);
        $this->entityManager->persist($anonymousUser);
        $this->entityManager->flush();

        return $anonymousUser;
    }
}
