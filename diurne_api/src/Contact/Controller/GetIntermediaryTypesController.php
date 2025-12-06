<?php

declare(strict_types=1);

namespace App\Contact\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contact\Bus\Query\GetIntermediaryTypes\GetIntermediaryTypesQuery;
use App\User\Entity\User;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetIntermediaryTypesController extends CommandQueryController
{
    #[Route('/api/intermediaryTypes', name: 'get_intermediaryTypes', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get intermediaryTypes',
        content: new Model(type: User::class)
    )]
    #[OA\RequestBody(
        description: 'IntermediaryType data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'int'),
            ]
        ))]
    #[OA\Tag(name: 'Contact')]
    public function __invoke(
    ): JsonResponse {
        if (!$this->isGranted('read', 'contact')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        $getIntermediaryTypes = new GetIntermediaryTypesQuery();
        $response = $this->ask($getIntermediaryTypes);

        return SuccessResponse::create(
            'get_intermediaryTypes',
            $response
        );
    }
}
