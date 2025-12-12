<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\UserMobileApp;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\User\UpdateUserMobileApp\UpdateUserMobileAppCommand;
use App\MobileAppApi\DTO\User\UpdateUserMobileAppRequestDto;
use App\MobileAppApi\Entity\UserMobileApp;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateUserMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/users/{id}', name: 'update_mobile_user', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 200,
        description: 'User update',
        content: new Model(type: UserMobileApp::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'permissionId', type: 'integer'),
                new OA\Property(property: 'isActive', type: 'boolean'),
                new OA\Property(property: 'picture', type: 'string'),
            ]
        )
    )]
    public function __invoke(
        int $id,
        #[MapRequestPayload] UpdateUserMobileAppRequestDto $requestDto
    ): JsonResponse {
        $command = new UpdateUserMobileAppCommand(
            $id,
            $requestDto->name,
            $requestDto->email,
            $requestDto->isActive,
            $requestDto->permissionId,
            $requestDto->picture
        );

        $response = $this->handle($command);
        return SuccessResponse::create('update_mobile_user', $response->toArray(), 'User updated');
    }
}
