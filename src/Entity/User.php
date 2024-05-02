<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Length(min: 8, minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères')]
    #[Assert\NotBlank(groups: ['register'])]
    #[Assert\NotNull(groups: ['register'])]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $firstname = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $lastname = null;

    #[ORM\Column(length: 120, nullable: false)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/')]
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(max: 120)]
    private ?string $email = null;

    #[ORM\Column(length: 12, nullable: true)]
    #[Assert\Regex(pattern: '/^0[1-9]([-. ]?[0-9]{2}){4}$/')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $administrator = false;

    #[ORM\Column]
    private ?bool $disabled = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private ?Campus $campus = null;

    #[ORM\Column(length: 256, nullable: true)]
    private ?string $profilePictureFilename = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): static
    {
        $this->administrator = $administrator;

        return $this;
    }

    public function isDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getProfilePictureFilename(): ?string
    {
        return $this->profilePictureFilename;
    }

    public function setProfilePictureFilename(?string $profilePictureFilename): void
    {
        $this->profilePictureFilename = $profilePictureFilename;
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
