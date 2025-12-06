<?php

declare(strict_types=1);

namespace App\User\DTO;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents the data transfer object for user registration requests.
 */
class SignInRequestDto implements PasswordAuthenticatedUserInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.', )]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 255, )]
        public string $password
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
