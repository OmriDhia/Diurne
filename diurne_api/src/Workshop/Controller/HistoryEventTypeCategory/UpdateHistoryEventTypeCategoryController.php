<?php

namespace App\Workshop\Controller\HistoryEventTypeCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\UpdateHistoryEventTypeCategory\UpdateHistoryEventTypeCategoryCommand;
use App\Workshop\Bus\Query\GetHistoryEventTypeCategoryByid\HistoryEventTypeCategoryResponse;
use App\Workshop\DTO\HistoryEventTypeCategory\UpdateHistoryEventTypeCategoryDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class UpdateHistoryEventTypeCategoryController extends CommandQueryController
{
    #[Route('/api/historyEventTypeCategory/{id}', name: 'history_event_type_category_update', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'History event type category updated',
        content: new Model(type: HistoryEventTypeCategoryResponse::class)
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'eventTypeId', type: 'integer'),
                new OA\Property(property: 'eventCategoryId', type: 'integer')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'History event type category ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        int                                                    $id,
        #[MapRequestPayload] UpdateHistoryEventTypeCategoryDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('update', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new UpdateHistoryEventTypeCategoryCommand(
                $id,
                $dto->eventTypeId,
                $dto->eventCategoryId
            );
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_type_category_updated',
                $response->toArray(),
                'History event type category updated successfully.'
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