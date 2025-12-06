<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\GetProfileById\GetProfileByIdQuery;
use App\User\Entity\Profile;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetProfileByIdController extends CommandQueryController
{
    #[Route('/api/profile/{id}', name: 'get_profile_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get profile by id',
        content: new Model(type: Profile::class)
    )]
    #[OA\RequestBody(
        description: 'Profile data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Profile')]
    public function __invoke(
        $id
    ): JsonResponse {
        if (!$this->isGranted('read', 'profile')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getUserByIdQuery = new GetProfileByIdQuery($id);
        $response = $this->ask($getUserByIdQuery);

        return SuccessResponse::create(
            'get_profile_by_id',
            $response,
            'Profile retrieved successfully'
        );
    }
}
