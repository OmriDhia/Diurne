<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\User\CreateUserMobileApp\CreateUserMobileAppCommand;
use App\MobileAppApi\DTO\User\CreateUserMobileAppRequestDto;
use App\MobileAppApi\Entity\UserMobileApp;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateUserMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/users', name: 'create_mobile_user', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'User creation',
        content: new Model(type: UserMobileApp::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'permissionId', type: 'integer'),
                new OA\Property(property: 'isActive', type: 'boolean'),
                new OA\Property(property: 'picture', type: 'string'),
            ]
        )
    )]
    public function __invoke(
        #[MapRequestPayload] CreateUserMobileAppRequestDto $requestDto
    ): JsonResponse {
        $command = new CreateUserMobileAppCommand(
            $requestDto->name,
            $requestDto->email,
            $requestDto->password,
            $requestDto->permissionId,
            $requestDto->isActive,
            $requestDto->picture
        );

        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_mobile_user',
            $response->toArray(),
            'User created successfully'
        );
    }
}
