<?php

declare(strict_types=1);

namespace App\User\Bus\Command\SignIn;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ResourceNotFoundException;
use App\User\Entity\User;
use App\User\Exception\InvalidCredentialsException;
use App\User\Repository\UserRepository;
use App\User\Security\AuthService;

class SignInCommandHandler implements CommandHandler
{
    public function __construct(private readonly UserRepository $userRepository, private readonly AuthService $authService) {}

    public function __invoke(SignInCommand $command): ApiTokenResponse
    {
        if (empty($command->email())) {
            throw new InvalidCredentialsException();
        }

        $user = $this->userRepository->findByEmail($command->email());

        if (!$user instanceof User) {
            throw new ResourceNotFoundException();
        }

        if (!password_verify($command->plainPassword(), $user->getPassword())) {
            throw new InvalidCredentialsException('Invalid password');
        }

        $jwt = $this->authService->authenticate([
            'email' => $user->getEmail(),
        ]);

        return new ApiTokenResponse($user->getId(), $jwt);
    }
}
