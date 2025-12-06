<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents the data transfer object for user registration requests.
 */
class RegistrationRequestDto implements PasswordAuthenticatedUserInterface
{
    /**
     * Constructor for the class.
     *
     * @param string        $firstname the firstname for the user
     * @param string        $lastname  the lastname for the user
     * @param string        $email     the lastname for the user
     * @param string        $password  the password for the user
     * @param string[]|null $roles     The roles assigned to the user. Can be null.
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.', )]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3, max: 255,
        )]
        public string $password,
        public ?array $roles,
        public string $firstname = '',
        public string $lastname = '',
        public string $genderId = '',
        public bool $isActive = true,
    ) {
    }

    /**
     * Gets the password.
     *
     * @return string|null the password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
