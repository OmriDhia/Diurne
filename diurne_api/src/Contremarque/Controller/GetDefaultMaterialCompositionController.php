<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetDefaultComposition\GetDefaultCompositionQuery;
use App\Contremarque\Bus\Query\GetDefaultComposition\GetDefaultCompositionQueryResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetDefaultMaterialCompositionController extends CommandQueryController
{
    #[Route('/api/default-material-composition', name: 'get_default_material_composition', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get default material composition',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'materialId', type: 'integer'),
                new OA\Property(property: 'percentage', type: 'float'),
                new OA\Property(property: 'qualityId', type: 'integer'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $GetDefaultCompositionQuery = new GetDefaultCompositionQuery();
        /** @var GetDefaultCompositionQueryResponse $response */
        $response = $this->ask($GetDefaultCompositionQuery);

        return SuccessResponse::create(
            'default-material-composition',
            $response->toArray()
        );
    }
}
