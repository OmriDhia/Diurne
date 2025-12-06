<?php

/*
 * This file is part of the CoopTilleulsForgotPasswordBundle package.
 *
 * (c) Vincent CHALAMON <vincent@les-tilleuls.coop>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Response\SuccessResponse;
use App\User\Repository\UserRepository;
use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use CoopTilleuls\ForgotPasswordBundle\Manager\ForgotPasswordManager;
use CoopTilleuls\ForgotPasswordBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @author Vincent CHALAMON <vincent@les-tilleuls.coop>
 */
final readonly class UpdatePassword
{
    public function __construct(private ForgotPasswordManager $forgotPasswordManager, private UserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher) {}

    /**
     * @param string $password
     *
     * @return Response
     */
    public function __invoke(AbstractPasswordToken $token, $password, ProviderInterface $provider)
    {
        $this->forgotPasswordManager->updatePassword($token, $password, $provider);
        $user = $token->getUser();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
        $this->userRepository->flush();

        return SuccessResponse::create(
            'update_password',
            [
                'user_id' => $user->getId(),
            ],
            'password update is successful'
        );
    }
}
