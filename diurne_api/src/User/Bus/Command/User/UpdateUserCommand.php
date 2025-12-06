<?php

declare(strict_types=1);

namespace App\User\Bus\Command\User;

use App\Common\Bus\Command\Command;

final class UpdateUserCommand implements Command
{
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $genderId;
    private bool $isActive = true;

    /**
     * UpdateUserCommand constructor.
     */
    public function __construct(
        public string $userId
    ) {
    }

    /**
     * Gets the email of the user.
     *
     * @return string The username of the user
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function setGenderId(string $genderId): self
    {
        $this->genderId = $genderId;

        return $this;
    }

    public function getGenderId(): string
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
