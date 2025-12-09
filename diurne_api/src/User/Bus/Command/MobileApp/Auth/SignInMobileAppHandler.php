<?php

declare(strict_types=1);

namespace App\User\Bus\Command\MobileApp\Auth;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Bus\Command\CommandResponse;
use App\User\Bus\Command\SignIn\ApiTokenResponse;
use App\User\Entity\UserMobileApp;
use App\User\Exception\InvalidCredentialsException;
use App\User\Repository\UserMobileAppRepository;
use App\User\Security\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SignInMobileAppHandler implements CommandHandler
{
    public function __construct(
        private readonly UserMobileAppRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AuthService $authService
    ) {}

    public function __invoke(SignInMobileAppCommand $command): ApiTokenResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $command->email]);

        if (!$user instanceof UserMobileApp) {
            throw new InvalidCredentialsException();
        }

        if (!$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new InvalidCredentialsException('Invalid password');
        }

        if (!$user->isActive()) {
            throw new InvalidCredentialsException('Account is inactive');
        }

        $jwt = $this->authService->authenticate([
            'email' => $user->getEmail(),
            'id' => $user->getId(),
            'role' => 'ROLE_MOBILE_USER' // Custom claim if needed
        ]);

        return new ApiTokenResponse($user->getId(), $jwt);
    }
}
