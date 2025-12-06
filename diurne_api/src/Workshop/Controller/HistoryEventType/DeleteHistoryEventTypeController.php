<?php

namespace App\Workshop\Controller\HistoryEventType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\DeleteHistoryEventType\DeleteHistoryEventTypeCommand;
use App\Workshop\Bus\Command\DeleteHistoryEventType\DeleteHistoryEventTypeResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DeleteHistoryEventTypeController extends CommandQueryController
{
    #[Route('/api/historyEventTypes/{id}', name: 'history_event_type_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'History event type deleted',
        content: new Model(type: DeleteHistoryEventTypeResponse::class)
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
        if (!$this->isGranted('delete', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new DeleteHistoryEventTypeCommand($id);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_type_deleted',
                $response->toArray(),
                'History event type deleted successfully.'
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['code' => 404, 'message' => $e->getMessage()],
                JsonResponse::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}