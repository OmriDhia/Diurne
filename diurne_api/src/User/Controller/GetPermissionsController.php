<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\GetPermissions\GetPermissionsQuery;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetPermissionsController extends CommandQueryController
{
    #[Route('/api/permissions', name: 'get_permissions', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get permissions',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'Permission data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'int'),
                new OA\Property(property: 'profile_id', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'Permission')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'permission')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getPermissions = new GetPermissionsQuery();
        $response = $this->ask($getPermissions);

        return SuccessResponse::create(
            'get_permissions',
            $response,
            'Permissions retrieved successfully'
        );
    }
}
