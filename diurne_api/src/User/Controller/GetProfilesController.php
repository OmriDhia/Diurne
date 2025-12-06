<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\GetProfiles\GetProfilesQuery;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetProfilesController extends CommandQueryController
{
    #[Route('/api/profiles', name: 'get_profiles', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get profiles',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'Profile data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'int'),
                new OA\Property(property: 'discount', type: 'float'),
            ]
        )
    )]
    #[OA\Tag(name: 'Profile')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'profile')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getProfiles = new GetProfilesQuery();
        $response = $this->ask($getProfiles);

        return SuccessResponse::create(
            'get_profiles',
            $response,
            'Profiles retrieved successfully'
        );
    }
}
