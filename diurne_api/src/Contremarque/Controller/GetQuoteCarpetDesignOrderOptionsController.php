<?php

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetQuoteCarpetDesignOrderOptions\GetQuoteDetailCarpetDesignOrderOptionsQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/quoteDetail/{quoteDetailId}/carpet-design-order-options', name: 'get_quote_detail_carpet_design_order_options', methods: ['GET'])]
#[OA\Get(
    path: '/api/quoteDetail/{quoteDetailId}/carpet-design-order-options',
    summary: 'Get a QuoteId by its ID',
    parameters: [
        new OA\Parameter(
            name: 'quoteDetailId',
            in: 'path',
            description: 'The ID of the quoteDetail',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Returns design-order-options for a quoteDetailId',
            content: new OA\JsonContent(type: 'object')
        ),
        new OA\Response(
            response: 404,
            description: 'Options not found'
        ),
    ]
)]
#[OA\Tag(name: 'Devis')]
class GetQuoteCarpetDesignOrderOptionsController extends CommandQueryController
{
    public function __invoke(int $quoteDetailId): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        try {
            /** @var CarpetDesignOrderOptionsResponse $response */
            $response = $this->ask(new GetQuoteDetailCarpetDesignOrderOptionsQuery($quoteDetailId));
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }

        return SuccessResponse::create(
            'get_quote_detail_carpet_design_order_options',
            $response->getData()
        );
    }
}
