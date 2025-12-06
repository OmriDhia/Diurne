<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetLayersByCarpetComposition\GetLayersByCarpetCompositionQuery;
use App\Contremarque\Bus\Query\GetLayersByCarpetComposition\GetLayersByCarpetCompositionResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetLayersByCarpetCompositionController extends CommandQueryController
{
    #[Route('/api/carpet-composition/{id}/layers', name: 'get_layers_by_carpet_composition', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get layers by carpet composition',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'layer_number', type: 'integer'),
                new OA\Property(property: 'remarque', type: 'string'),
            ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request, int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $getLayersQuery = new GetLayersByCarpetCompositionQuery($id);
        /** @var GetLayersByCarpetCompositionResponse $response */
        $response = $this->ask($getLayersQuery);

        return SuccessResponse::create(
            'get_layers_by_carpet_composition',
            $response->toArray()
        );
    }
}
