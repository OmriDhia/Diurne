<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Permission\CreatePermissionCommand;
use App\User\DTO\CreatePermissionRequestDto;
use App\User\Entity\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreatePermissionController extends CommandQueryController
{
    #[Route('/api/createPermission', name: 'permission_creation', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Permission creation',
        content: new Model(type: Permission::class)
    )]
    #[OA\RequestBody(
        description: 'Permission data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'public_name', type: 'string'),
                new OA\Property(property: 'guard_name', type: 'string'),
                new OA\Property(property: 'entity', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Permission')]
    public function __invoke(
        #[MapRequestPayload] CreatePermissionRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('create', 'permission')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $createPermissionCommand = new CreatePermissionCommand(
            $requestDTO->name,
            $requestDTO->public_name,
            $requestDTO->guard_name,
            $requestDTO->entity
        );

        $permissionResponse = $this->handle($createPermissionCommand);

        return SuccessResponse::create(
            'permission_creation',
            $permissionResponse->toArray(),
        );
    }
}
