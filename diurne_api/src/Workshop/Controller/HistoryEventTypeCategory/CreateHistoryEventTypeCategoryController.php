<?php

namespace App\Workshop\Controller\HistoryEventTypeCategory;

use App\Common\Controller\CommandQueryController;
use App\Common\Response\SuccessResponse;
use App\Workshop\Bus\Command\CreateHistoryEventTypeCategory\CreateHistoryEventTypeCategoryCommand;
use App\Workshop\Bus\Command\CreateHistoryEventTypeCategory\HistoryEventTypeCategoryResponse;
use App\Workshop\DTO\HistoryEventTypeCategory\CreateHistoryEventTypeCategoryDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CreateHistoryEventTypeCategoryController extends CommandQueryController
{
    #[Route('/api/historyEventTypeCategory', name: 'history_event_type_category_create', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'History event type category created',
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
    #[OA\Tag(name: 'Workshop')]
    public function __invoke(
        #[MapRequestPayload] CreateHistoryEventTypeCategoryDto $dto
    ): JsonResponse
    {
        if (!$this->isGranted('create', 'workshop')) {
            return new JsonResponse(
                ['code' => 403, 'message' => 'Forbidden'],
                JsonResponse::HTTP_FORBIDDEN
            );
        }

        try {
            $command = new CreateHistoryEventTypeCategoryCommand(
                $dto->eventTypeId,
                $dto->eventCategoryId
            );
            $response = $this->handle($command);

            return SuccessResponse::create(
                'history_event_type_category_created',
                $response->toArray(),
                'History event type category created successfully.',
                JsonResponse::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['code' => 400, 'message' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}