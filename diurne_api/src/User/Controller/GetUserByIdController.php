<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\GetUserById\GetUserByIdQuery;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetUserByIdController extends CommandQueryController
{
    #[Route('/api/user/{id}', name: 'get_user_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'User creation',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'User data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(
        $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getUserByIdQuery = new GetUserByIdQuery($id);
        $response = $this->ask($getUserByIdQuery);

        return SuccessResponse::create(
            'get_user_by_id',
            $response,
            'User retrieved successfully'
        );
    }
}
