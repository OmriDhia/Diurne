<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignUp;

use App\Common\Bus\Command\Command;

/**
 * Class CreateUserCommand.
 *
 * This class represents a command for creating a new user in the application. It implements the Command interface.
 * It encapsulates the necessary information for creating a user, such as username, password, and roles.
 */
final class CreateUserCommand implements Command
{
    private string $firstname;
    private string $lastname;
    private array $roles;
    private bool $isActive = true;

    /**
     * CreateUserCommand constructor.
     *
     * Initializes a new instance of the CreateUserCommand class with the provided username, password, and roles.
     *
     * @param string $email    The email for the user
     * @param string $password The password for the user
     */
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {
    }

    /**
     * Gets the username of the user.
     *
     * @return string The username of the user
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Gets the password of the user.
     *
     * @return string The password of the user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Gets the roles assigned to the user.
     *
     * @return string[]|null The roles assigned to the user, or null if no roles are assigned
     */
    public function roles(): ?array
    {
        return $this->roles;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function setLastName(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setGenderId(string $genderId): self
    {
        $this->genderId = $genderId;

        return $this;
    }

    public function getGenderId()
    {
        return $this->genderId;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
