<?php

declare(strict_types=1);

namespace App\User\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\User\Bus\Query\GetGenders\GetGendersQuery;
use App\User\Entity\Gender;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetGendersController extends CommandQueryController
{
    #[Route('/api/genders', name: 'get_genders', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get genders',
        content: new Model(type: Gender::class)
    )]
    #[OA\RequestBody(
        description: 'Gender data'
    )]
    #[OA\Tag(name: 'User')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'user')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }

        $getGenders = new GetGendersQuery();

        $response = $this->ask($getGenders);

        return SuccessResponse::create(
            'get_users',
            $response,
            'Users genders retrieved successfully'

        );
    }
}
