<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User entity.
 *
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 * @package: App\Entity
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int the user id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     *
     * @var string the user username
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @var string the user password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     *
     * @var string the user email
     */
    private $email;

    /**
     * @ORM\Column(type="json", nullable=true)
     *
     * @var array the user roles
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="user")
     *
     * @var Collection|Task[] the user tasks
     */
    private $tasks;

    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get the user id.
     *
     * @return int the user id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user username identifier.
     *
     * @return string|null the user username
     */
    public function getUserIdentifier(): ?string
    {
        return $this->getUsername();
    }

    /**
     * Get the user username.
     *
     * @return string|null the user username
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the user username.
     *
     * @param string $username the user username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Get the user password.
     *
     * @return string|null the user password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the user password.
     *
     * @param string $password the user password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Get the user email.
     *
     * @return string|null the user email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the user email.
     *
     * @param string $email the user email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function eraseCredentials()
    {
    }

    /**
     * Get the user roles.
     *
     * @return array the user roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Set the user roles.
     *
     * @param array $roles the user roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the user tasks.
     *
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * Add a task to the user.
     *
     * @param Task $task the task to add
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setUser($this);
        }

        return $this;
    }

    /**
     * Remove a task from the user.
     *
     * @param Task $task the task to remove
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Check if user is anonymous.
     *
     * @return bool true if user is anonymous
     */
    public function isAnonymous(): ?bool
    {
        return 'anonymous' === $this->getUsername();
    }
}
