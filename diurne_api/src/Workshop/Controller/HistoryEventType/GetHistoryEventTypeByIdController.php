<?php
declare(strict_types=1);

namespace App\Workshop\Controller\HistoryEventType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Query\GetHistoryEventTypeById\GetHistoryEventTypeByIdQuery;
use App\Workshop\Entity\HistoryEventType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetHistoryEventTypeByIdController extends CommandQueryController
{
    #[Route('/api/historyEventTypes/{id}', name: 'history_event_type_get', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a single history event type',
        content: new Model(type: HistoryEventType::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'History event type not found'
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'History event type ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(int $id): JsonResponse
    {
        $query = new GetHistoryEventTypeByIdQuery($id);
        $response = $this->ask($query);

        if (empty($response->toArray())) {
            return new JsonResponse(
                ['code' => 404, 'message' => 'History event type not found'],
                404
            );
        }

        return SuccessResponse::create(
            'history_event_type_retrieved',
            $response->toArray()
        );
    }
}