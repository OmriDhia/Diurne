<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\HasPermissionTo\HasPermissionToQuery;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HasPermissionToController extends CommandQueryController
{
    #[Route('/api/user/{userId}/hasPermissionTo/{permissionId}', name: 'has_permission_to', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'User has permission to',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'User has permission to',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'permissionId', type: 'int'),
                new OA\Property(property: 'userId', type: 'int'),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        $userId,
        $permissionId
    ): JsonResponse {
        if (!$this->isGranted('read', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $hasPermissionToQuery = new HasPermissionToQuery($permissionId, $userId);
        $profilePermissionResponse = $this->ask($hasPermissionToQuery);

        return SuccessResponse::create(
            'has_permission_to',
            $profilePermissionResponse->status,
            'User has permission to'
        );
    }
}
