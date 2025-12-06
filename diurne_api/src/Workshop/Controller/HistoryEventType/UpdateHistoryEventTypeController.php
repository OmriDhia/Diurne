<?php

namespace App\Workshop\Controller\HistoryEventType;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateHistoryEventType\UpdateHistoryEventTypeCommand;

use App\Workshop\DTO\HistoryEventTypes\UpdateHistoryEventTypeDto;
use App\Workshop\Entity\HistoryEventType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateHistoryEventTypeController extends CommandQueryController
{
    #[Route('/api/historyEventTypes/{id}', name: 'history_event_type_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'History event type updated',
        content: new Model(type: HistoryEventType::class)
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'name', type: 'string', maxLength: 50)]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'History event type ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                            $id,
        #[MapRequestPayload] UpdateHistoryEventTypeDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new UpdateHistoryEventTypeCommand($id, $dto->name);
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_type_updated',
                $response->toArray(),
                'History event type updated successfully.'
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