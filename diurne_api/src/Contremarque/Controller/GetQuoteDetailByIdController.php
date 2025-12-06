<?php

namespace App\Contremarque\Controller;

use Exception;
use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Contremarque\Bus\Query\GetQuoteDetailById\GetQuoteDetailByIdQuery;
use App\Contremarque\Bus\Query\GetQuoteDetailById\GetQuoteDetailByIdResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/quoteDetail/{id}', name: 'get_quoteDetail_by_id', methods: ['GET'])]
#[OA\Get(
    path: '/api/quoteDetail/{id}',
    summary: 'Get a QuoteDetail by its ID',
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            description: 'The ID of the QuoteDetail',
            required: true,
            schema: new OA\Schema(type: 'integer')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Returns the details of a QuoteDetail',
            content: new OA\JsonContent(type: 'object')
        ),
        new OA\Response(
            response: 404,
            description: 'QuoteDetail not found'
        ),
    ]
)]
#[OA\Tag(name: 'Devis')]
class GetQuoteDetailByIdController extends CommandQueryController
{
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse(['code' => 401, 'message' => 'Unauthorized to access this content'], 401);
        }
        try {
            /** @var GetQuoteDetailByIdResponse $response */
            $response = $this->ask(new GetQuoteDetailByIdQuery($id));
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }

        return SuccessResponse::create(
            'get_quoteDetail_by_id',
            $response
        );
    }
}
