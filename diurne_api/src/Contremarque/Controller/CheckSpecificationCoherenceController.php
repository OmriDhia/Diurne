<?php

declare(strict_types=1);

namespace App\Contremarque\Controller;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\CheckSpecificationCoherence\CheckSpecificationCoherenceQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CheckSpecificationCoherenceController extends CommandQueryController
{
    #[Route('/api/specificationCoherence', name: 'check_specification_coherence', methods: ['GET'])]
    #[OA\Get(
        path: '/api/specification-coherence',
        summary: 'Check coherence between QuoteDetail and CarpetDesignOrder specifications',
        parameters: [
            new OA\Parameter(
                name: 'carpetDesignOrderId',
                in: 'query',
                description: 'The ID of the CarpetDesignOrder',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'quoteDetailId',
                in: 'query',
                description: 'The ID of the QuoteDetail',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the coherence check result',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'isCoherent', type: 'boolean'),
                        new OA\Property(
                            property: 'differences',
                            type: 'object',
                            additionalProperties: true
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Resource not found'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized access'
            ),
        ]
    )]
    #[OA\Tag(name: 'Contremarque')]
    public function __invoke(Request $request): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(
                ['code' => 401, 'message' => 'Unauthorized to access this content'],
                401
            );
        }

        $carpetDesignOrderId = (int)$request->query->get('carpetDesignOrderId');
        $quoteDetailId = (int)$request->query->get('quoteDetailId');

        if (!$carpetDesignOrderId || !$quoteDetailId) {
            return new JsonResponse(
                ['code' => 400, 'message' => 'Missing required query parameters'],
                400
            );
        }

        try {
            $query = new CheckSpecificationCoherenceQuery($carpetDesignOrderId, $quoteDetailId);
            $response = $this->ask($query);

            return SuccessResponse::create(
                'check_specification_coherence',
                $response->toArray(),
                $response->isCoherent() ? 'Specifications are coherent' : 'Specifications have differences'
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                404
            );
        }
    }
}