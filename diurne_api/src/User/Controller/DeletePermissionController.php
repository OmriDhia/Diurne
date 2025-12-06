<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Command\Permission\DeletePermissionCommand;
use App\User\DTO\DeletePermissionRequestDto;
use App\User\Entity\Permission;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class DeletePermissionController extends CommandQueryController
{
    #[Route('/api/deletePermission', name: 'permission_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Permission delete',
        content: new Model(type: Permission::class)
    )]
    #[OA\RequestBody(
        description: 'Permission data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Permission')]
    public function __invoke(
        #[MapRequestPayload] DeletePermissionRequestDto $requestDTO
    ): JsonResponse {
        if (!$this->isGranted('delete', 'permission')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $deletePermissionCommand = new DeletePermissionCommand(
            $requestDTO->name
        );
        $permissionResponse = $this->handle($deletePermissionCommand);

        return SuccessResponse::create(
            'permission_delete',
            $permissionResponse->toArray(),
            'Permission deleted successfully'
        );
    }
}
