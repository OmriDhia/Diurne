<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\GetUsers\GetUsersQuery;
use App\User\DTO\GetUsersQueryDto;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use function filter_var;
use const FILTER_NULL_ON_FAILURE;
use const FILTER_VALIDATE_BOOLEAN;

class GetUsersController extends CommandQueryController
{
    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get users',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'page', type: 'int'),
                new OA\Property(property: 'itemPerPage', type: 'int'),
                new OA\Property(property: 'isActive', type: 'boolean'),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        #[MapQueryString] GetUsersQueryDto $query,
    ): JsonResponse {
        if (!$this->isGranted('read', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $isActive = $query->filter->isActive ?? null;
        if (is_string($isActive)) {
            $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }

        $getUsers = new GetUsersQuery(
            $query->page ?? null,
            $query->itemPerPage ?? null,
            $query->filter->firstname ?? null,
            $query->filter->lastname ?? null,
            $query->filter->email ?? null,
            $query->filter->profileId ?? null,
            $query->filter->gender ?? null,
            $query->filter->profiles ?? null,
            $isActive
        );

        $response = $this->ask($getUsers);

        return SuccessResponse::create(
            'get_users',
            $response,
            'Users retrieved successfully'
        );
    }
}
