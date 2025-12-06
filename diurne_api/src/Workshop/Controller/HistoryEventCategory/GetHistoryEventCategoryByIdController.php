<?php
declare(strict_types=1);

namespace App\Workshop\Controller\HistoryEventCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetHistoryEventCategoryById\GetHistoryEventCategoryByIdQuery;
use App\Workshop\Bus\Query\GetHistoryEventTypeById\GetHistoryEventTypeByIdQuery;
use App\Workshop\Entity\HistoryEventCategory;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetHistoryEventCategoryByIdController extends CommandQueryController
{
    #[Route('/api/historyEventCategory/{id}', name: 'history_event_category_get', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a single history event category',
        content: new Model(type: HistoryEventCategory::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'History event category not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'History event category ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetHistoryEventCategoryByIdQuery($id);
        $response = $this->ask($query);

        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'History event category not found'],
                404
            );
        }

        return SuccessResponse::create(
            'history_event_category_retrieved',
            $response->toArray()
        );
    }
}