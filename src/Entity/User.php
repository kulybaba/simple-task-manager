<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, groups={"registration"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(
     *     message="Email should not be blank",
     *     groups={"login", "registration"}
     * )
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email",
     *     groups={"login", "registration"}
     * )
     *
     * @var string $email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @var array $roles
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *     message="Password should not be blank",
     *     groups={"login"}
     * )
     * @Assert\Length(
     *     max="30",
     *     min="8",
     *     maxMessage="Password must contain maximum 30 characters",
     *     minMessage="Password must contain minimum 8 characters",
     *     groups={"login"}
     * )
     *
     * @var string $password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="First name should not be blank",
     *     groups={"registration"}
     * )
     * @Assert\Length(
     *     max="20",
     *     min="2",
     *     maxMessage="First name must contain maximum 20 characters",
     *     minMessage="First name must contain minimum 2 characters",
     *     groups={"registration"}
     * )
     *
     * @var string $firstName
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Last name should not be blank",
     *     groups={"registration"}
     * )
     * @Assert\Length(
     *     max="20",
     *     min="2",
     *     maxMessage="Last name must contain maximum 20 characters",
     *     minMessage="Last name must contain minimum 2 characters",
     *     groups={"registration"}
     * )
     *
     * @var string $lastName
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="API token should not be blank",
     *     groups={"registration"}
     * )
     *
     * @var string $apiToken
     */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="user")
     *
     * @var Collection|Project[] $projects
     */
    private $projects;

    /**
     * @Assert\NotBlank(
     *     message="Password should not be blank",
     *     groups={"registration"}
     * )
     * @Assert\Length(
     *     max="30",
     *     min="8",
     *     maxMessage="Password must contain maximum 30 characters",
     *     minMessage="Password must contain minimum 8 characters",
     *     groups={"registration"}
     * )
     *
     * @var string $plainPassword
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool $verified
     */
    private $verified = false;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return string
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    /**
     * @param string $apiToken
     * @return $this
     */
    public function setApiToken(string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setUser($this);
        }

        return $this;
    }

    /**
     * @param Project $project
     * @return $this
     */
    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return $this
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     * @return $this
     */
    public function setIsVerified(bool $verified): self
    {
        $this->verified = $verified;

        return $this;
    }
}
