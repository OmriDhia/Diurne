<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Command\Commercial\CreateCommercialCommand;
use App\User\DTO\RegistrationRequestDto;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class CreateCommercialController extends CommandQueryController
{
    #[Route('/api/createCommercial', name: 'create_commercial', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Commercial creation',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'Commercial data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'fisrtname', type: 'string'),
                new OA\Property(property: 'lastname', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'genderId', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
        #[MapRequestPayload] RegistrationRequestDto $requestDTO,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $password = $passwordHasher->hashPassword($requestDTO, $requestDTO->password);
        $createCommercialCommand = new CreateCommercialCommand($requestDTO->email, $password);
        $createCommercialCommand->setFirstName($requestDTO->fistname ?? '');
        $createCommercialCommand->setLastName($requestDTO->lastname ?? '');
        $createCommercialCommand->setGenderId($requestDTO->genderId ?? '');
        $createCommercialCommand->setRoles(['ROLE_USER']);
        $createCommercialResponse = $this->handle($createCommercialCommand);
        // flush here

        return SuccessResponse::create(
            'create_commercial',

            [
                'user_id' => $createCommercialResponse->getUserId(),
                'email' => $createCommercialResponse->getEmail(),
            ],
            'Commercial created successfully'

        );
    }
}
