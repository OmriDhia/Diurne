<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignUp;

use App\Common\Exception\ValidationException;
use App\Common\Exception\DuplicateValidationResourceException;
use App\User\Entity\Gender;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\UserRepository;
use App\User\Validation\Trait\RolesValidationTrait;

/**
 * UserCreator is responsible for handling the user creation logic.
 * It checks for duplicate users and persists the new user to the repository.
 */
final class UserCreator
{
    use RolesValidationTrait;

    /**
     * Constructor for UserCreator.
     *
     * @param UserRepository $userRepository repository for user persistence
     */
    public function __construct(
        private UserRepository $userRepository,
        private GenderRepository $genderRepository
    ) {
    }

    /**
     * Creates a new user and saves it to the repository.
     *
     * @return User the newly created user
     *
     * @throws DuplicateValidationResourceException if a user with the same username already exists
     */
    public function __invoke(CreateUserCommand $command): User
    {
        // Check if a user with the given email already exists.
        $existingUser = $this->userRepository->findByEmail($command->getEmail());
        if (null !== $existingUser) {
            throw new DuplicateValidationResourceException();
        }
        $roles = $this->getRoles($command->roles());
        $password = $command->getPassword();
        $email = $command->getEmail();
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setFirstname($command->getFirstName());
        $user->setLastname($command->getLastName());
        $user->setRoles($roles);
        $user->setIsActive($command->isActive());
        if ($command->getGenderId()) {
            $gender = $this->genderRepository->find((int) $command->getGenderId());
            if ($gender instanceof Gender) {
                $user->setGender($gender);
            }
        }
        $this->userRepository->save($user);
        $this->userRepository->flush();

        return $user;
    }

    private function getRoles($roles): array
    {
        $violations = $this->validateRoles($roles);
        if (!empty($violations)) {
            throw new ValidationException($violations);
        }

        return $roles;
    }
}
