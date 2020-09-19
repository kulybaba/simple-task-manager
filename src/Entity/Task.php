<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @var int $id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Text should not be blank",
     *     groups={"add-task", "edit-task"}
     * )
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Text must contain maximum 255 characters",
     *     groups={"add-task", "edit-task"}
     * )
     * 
     * @var string $text
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     * 
     * @var bool $completed
     */
    private $completed = false;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Project $project
     */
    private $project;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int $position
     */
    private $position = 0;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(
     *     message="Deadline should not be blank",
     *     groups={"add-task", "edit-task"}
     * )
     *
     * @var \DateTimeInterface $deadline
     */
    private $deadline;

    public function __construct()
    {
        $this->deadline = new \DateTime(date('Y-m-d', strtotime('+1 day')));
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return $this
     */
    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     * @return $this
     */
    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    /**
     * @param \DateTimeInterface $deadline
     * @return $this
     */
    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }
}
