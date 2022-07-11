<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task entity.
 *
 * @ORM\Entity
 * @ORM\Table
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 * @package: App\Entity
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int the task id
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime the task creation date
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     *
     * @var string the task title
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     *
     * @var string the task content
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool the task status
     */
    private $isDone;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=true)
     *
     * @var User the task user
     */
    private $user;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isDone = false;
    }

    /**
     * Get the task id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the task creation date.
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the task creation date.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the task title.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the task title.
     *
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * Get the task content.
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set the task content.
     *
     * @param string $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * Get the task status.
     *
     * @return bool true|false
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * Set the task status.
     *
     * @param bool $flag true|false
     */
    public function toggle($flag): void
    {
        $this->isDone = $flag;
    }

    /**
     * Get the task user.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the task user.
     *
     * @param User $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
