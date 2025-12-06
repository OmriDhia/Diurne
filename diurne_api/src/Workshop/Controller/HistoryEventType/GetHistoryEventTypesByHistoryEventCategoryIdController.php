<?php
declare(strict_types=1);

namespace App\Workshop\Controller\HistoryEventType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetHistoryEventTypesByHistoryEventCategoryId\GetHistoryEventTypesByHistoryEventCategoryIdQuery;
use App\Workshop\Entity\HistoryEventType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetHistoryEventTypesByHistoryEventCategoryIdController extends CommandQueryController
{
    #[Route('/api/historyEventTypes/category/{historyEventCategoryId}', name: 'history_event_types_get_by_history_event_category_id', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'List', content: new OA\JsonContent(properties: [new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: new Model(type: HistoryEventType::class)))]))]
    #[OA\Parameter(name: 'historyEventCategoryId', in: 'path', description: 'History event category id', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $historyEventCategoryId): JsonResponse
    {
        $response = $this->ask(new GetHistoryEventTypesByHistoryEventCategoryIdQuery($historyEventCategoryId));

        return SuccessResponse::create('history_event_types_list', $response->toArray());
    }
}
