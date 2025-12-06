<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\SignUp\CreateUserCommand;
use App\User\DTO\RegistrationRequestDto;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends CommandQueryController
{
    #[Route('/api/createUser', name: 'register_user', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'User creation',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'fisrtname', type: 'string'),
                new OA\Property(property: 'lastname', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'genderId', type: 'string'),
                new OA\Property(property: 'isActive', type: 'boolean'),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        #[MapRequestPayload] RegistrationRequestDto $requestDTO,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $password = $passwordHasher->hashPassword($requestDTO, $requestDTO->password);
        $createUserCommand = new CreateUserCommand($requestDTO->email, $password);
        $createUserCommand->setFirstName($requestDTO->fistname ?? '');
        $createUserCommand->setLastName($requestDTO->lastname ?? '');
        $createUserCommand->setGenderId($requestDTO->genderId ?? '');
        $createUserCommand->setIsActive($requestDTO->isActive ?? true);
        $createUserCommand->setRoles(['ROLE_USER']);
        $createUserResponse = $this->handle($createUserCommand);
        // flush here

        return SuccessResponse::create(
            'register_user',
            [
                'user_id' => $createUserResponse->getUserId(),
                'email' => $createUserResponse->getEmail(),
                'is_active' => $createUserResponse->isActive(),
            ],
            'User created successfully'
        );
    }
}
