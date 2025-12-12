<?php

declare(strict_types=1);

namespace App\MobileAppApi\Controller\MobileApp\Permissions;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\MobileAppApi\Bus\Command\Permissions\CreatePermissionMobileApp\CreatePermissionMobileAppCommand;
use App\MobileAppApi\Bus\Command\Permissions\UpdatePermissionMobileApp\UpdatePermissionMobileAppCommand;
use App\MobileAppApi\Bus\Command\Permissions\DeletePermissionMobileApp\DeletePermissionMobileAppCommand;
use App\MobileAppApi\Bus\Query\Permissions\GetPermissionMobileApp\GetPermissionMobileAppQuery;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use App\MobileAppApi\Entity\PermissionsMobileApp;

class PermissionsMobileAppController extends CommandQueryController
{
    #[Route('/api/mobile/permissions', name: 'create_mobile_permission', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Permission creation',
        content: new Model(type: PermissionsMobileApp::class)
    )]
    #[OA\RequestBody(
        description: 'Permission data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'description', type: 'string'),
            ]
        )
    )]
    public function create(
        #[MapRequestPayload] CreatePermissionMobileAppCommand $command
    ): JsonResponse {
        $response = $this->handle($command);

        return SuccessResponse::create(
            'create_mobile_permission',
            $response->toArray(),
            'Permission created successfully'
        );
    }

    #[Route('/api/mobile/permissions', name: 'get_mobile_permissions', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of permissions',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: PermissionsMobileApp::class))
        )
    )]
    public function get(): JsonResponse
    {
        $query = new GetPermissionMobileAppQuery();
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_mobile_permissions',
            $response->toArray(),
            'Permissions retrieved successfully'
        );
    }

    #[Route('/api/mobile/permissions/{id}', name: 'update_mobile_permission', methods: ['PUT', 'PATCH'])]
    #[OA\Response(
        response: 200,
        description: 'Permission update',
        content: new Model(type: PermissionsMobileApp::class)
    )]
    #[OA\RequestBody(
        description: 'Permission data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string'),
                new OA\Property(property: 'description', type: 'string'),
            ]
        )
    )]
    public function update(
        int $id,
        #[MapRequestPayload] UpdatePermissionMobileAppCommand $command
    ): JsonResponse {
        $command = new UpdatePermissionMobileAppCommand($id, $command->name, $command->description);
        $response = $this->handle($command);
        return SuccessResponse::create('update_mobile_permission', $response->toArray(), 'Permission updated');
    }

    #[Route('/api/mobile/permissions/{id}', name: 'delete_mobile_permission', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Permission deletion'
    )]
    public function delete(int $id): JsonResponse
    {
        $command = new DeletePermissionMobileAppCommand($id);
        $this->handle($command);

        return SuccessResponse::create('delete_mobile_permission', [], 'Permission deleted successfully');
    }
}
