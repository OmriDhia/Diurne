<?php

namespace App\Workshop\Controller\HistoryEventTypeCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetHistoryEventTypeCategoryByid\GetHistoryEventTypeCategoryByIdQuery;
use App\Workshop\Entity\HistoryEventTypeCategory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetHistoryEventTypeCategoryByIdController extends CommandQueryController
{
    #[Route('/api/historyEventTypeCategory/{id}', name: 'history_event_type_category_get', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Single history event type category',
        content: new Model(type: HistoryEventTypeCategory::class)
    )]
    #[OA\Response(response: 404, description: 'Not found')]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetHistoryEventTypeCategoryByIdQuery($id);
        $response = $this->ask($query);

        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'History event type category not found'],
                404
            );
        }

        return SuccessResponse::create(
            'history_event_type_category_retrieved',
            $response->toArray()
        );
    }
}