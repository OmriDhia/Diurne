<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignUp;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ValidationException;

/**
 * CreateUserCommandHandler handles the creation of new users.
 * Implements CommandHandler to integrate with command bus.
 */
final readonly class CreateUserCommandHandler implements CommandHandler
{
    /**
     * Constructor for CreateUserCommandHandler.
     *
     * @param UserCreator $creator the service responsible for creating new users
     */
    public function __construct(
        private UserCreator $creator
    ) {
    }

    /**
     * Invokes the command handler to process the CreateUserCommand.
     *
     * @param CreateUserCommand $command the command containing user creation data
     *
     * @return CreateUserResponse the response containing the ID of the created user
     *
     * @throws DuplicateValidationResourceException if a user with the same identifier already exists
     * @throws ValidationException
     */
    public function __invoke(CreateUserCommand $command): CreateUserResponse
    {
        // Create a new user with the provided details.
        // Return the response containing the new user's ID (Uuid).
        $user = $this->creator->__invoke($command);

        return new CreateUserResponse(
            (string) $user->getId(),
            (string) $user->getEmail(),
            $user->isActive(),
        );
    }
}
