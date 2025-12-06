<?php

declare(strict_types=1);

namespace App\Contact\Bus\Command\Commercial;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\DuplicateValidationResourceException;
use App\Common\Exception\ResourceNotFoundException;
use App\Common\Exception\ValidationException;
use App\User\Entity\Gender;
use App\User\Entity\Profile;
use App\User\Entity\User;
use App\User\Repository\GenderRepository;
use App\User\Repository\ProfileRepository;
use App\User\Repository\UserRepository;
use App\User\Validation\Trait\RolesValidationTrait;

/**
 * CreateCommercialCommandHandler handles the creation of new users.
 * Implements CommandHandler to integrate with command bus.
 */
final class CreateCommercialCommandHandler implements CommandHandler
{
    use RolesValidationTrait;

    public function __construct(
        private UserRepository    $userRepository,
        private GenderRepository  $genderRepository,
        private ProfileRepository $profileRepository
    )
    {
    }

    /**
     * Invokes the command handler to process the CreateCommercialCommand.
     *
     * @param CreateCommercialCommand $command the command containing user creation data
     *
     * @return CreateCommercialResponse the response containing the ID of the created user
     *
     * @throws DuplicateValidationResourceException if a user with the same identifier already exists
     * @throws ValidationException
     */
    public function __invoke(CreateCommercialCommand $command): CreateCommercialResponse
    {
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
        if ($command->getGenderId()) {
            $gender = $this->genderRepository->find((int)$command->getGenderId());
            if ($gender instanceof Gender) {
                $user->setGender($gender);
            }
        }
        $profile = $this->profileRepository->findOneByName('Commercial');

        if (!$profile instanceof Profile) {
            throw new ResourceNotFoundException();
        }

        $user->setProfile($profile);
        $this->userRepository->save($user);
        $this->userRepository->flush();

        return new CreateCommercialResponse(
            (string)$user->getId(),
            (string)$user->getEmail(),
        );
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
