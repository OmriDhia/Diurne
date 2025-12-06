<?php

namespace App\Contremarque\Controller\CarpetOrderDetail;

use App\Common\Controller\CommandQueryController;
use App\Contremarque\Bus\Query\GetCarpetOrderDetailById\GetCarpetOrderDetailByIdQuery;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

;

class GetCarpetOrderDetailByIdController extends CommandQueryController
{
    #[Route('/api/carpetOrdersDetail/{id}', name: 'carpet_order_Detail_by_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a single carpet order',
        content: new OA\JsonContent(ref: '#/components/schemas/CarpetOrder')
    )]
    #[OA\Response(
        response: 404,
        description: 'Carpet order not found'
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID of the carpet order',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Carpet Order')]
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->isGranted('read', 'contremarque')) {
            return new JsonResponse([
                'code' => 401,
                'message' => 'Unauthorized to access this content',
            ], 401);
        }

        $query = new GetCarpetOrderDetailByIdQuery($id);
        $response = $this->ask($query);

        if ($response === null) {
            return new JsonResponse([
                'code' => 404,
                'message' => 'Carpet order not found',
            ], 404);
        }

        return new JsonResponse($response->toArray(), 200);
    }
}