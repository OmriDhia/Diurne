<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\User\UpdateUserCommand;
use App\User\DTO\UpdateUserRequestDto;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UpdateUserController extends CommandQueryController
{
    #[Route('/api/user/{userId}/update', name: 'user_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'User update',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'firstname', type: 'string'),
                new OA\Property(property: 'lastname', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'pasword', type: 'string'),
                new OA\Property(property: 'genderId', type: 'string'),
                new OA\Property(property: 'isActive', type: 'boolean'),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        $userId,
        #[MapRequestPayload] UpdateUserRequestDto $requestDTO,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        if (!$this->isGranted('update', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $password = $passwordHasher->hashPassword($requestDTO, $requestDTO->password);
        $updateUserCommand = new UpdateUserCommand($userId);
        $updateUserCommand->setFirstName($requestDTO->firstname);
        $updateUserCommand->setLastName($requestDTO->firstname);
        $updateUserCommand->setEmail($requestDTO->email);
        $updateUserCommand->setPassword($password);
        $updateUserCommand->setGenderId($requestDTO->genderId ?? '');
        $updateUserCommand->setIsActive($requestDTO->isActive ?? true);
        $userResponse = $this->handle($updateUserCommand);

        return SuccessResponse::create(
            'update_user',
            $userResponse->toArray(),
            'User updated successfully'
        );
    }
}
