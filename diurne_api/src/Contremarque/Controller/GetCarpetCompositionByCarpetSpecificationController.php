<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetCarpetCompositionByCarpetSpecification\GetCarpetCompositionByCarpetSpecificationQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCarpetCompositionByCarpetSpecificationController extends CommandQueryController
{
    #[Route('/api/carpet-composition/{carpetSpecificationId}', name: 'get_carpet_composition_by_spec', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get carpet composition by carpet specification',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'trame', type: 'string'),
                new OA\Property(property: 'threadCount', type: 'integer'),
                new OA\Property(property: 'layerCount', type: 'integer'),
                new OA\Property(property: 'layers', type: 'array', items: new OA\Items(
                    properties: [new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'layer_number', type: 'integer'),
                        new OA\Property(property: 'remarque', type: 'string'),
                        new OA\Property(property: 'layer_details', type: 'array', items: new OA\Items(type: 'string'))]
                )), ]
        )
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(int $carpetSpecificationId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $query = new GetCarpetCompositionByCarpetSpecificationQuery($carpetSpecificationId);
        $response = $this->ask($query);

        return SuccessResponse::create(
            'get_carpet_composition_by_spec',
            $response->toArray()
        );
    }
}
